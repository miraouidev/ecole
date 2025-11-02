<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Groupe;
use App\Entity\GroupeMini;
use App\Entity\MatiereClasseProf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/scolaire/groupes')]
class GroupeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * 📌 Récupérer les infos d’un groupe : niveau, matières, profs
     */
    #[Route('/{id}/details', name: 'groupe_details', methods: ['GET'])]
    public function details(int $id): JsonResponse
    {
        $groupe = $this->em->getRepository(Groupe::class)->find($id);
        if (!$groupe) {
            return $this->json(['error' => 'Groupe introuvable'], 404);
        }

        // Récupérer toutes les matières de ce groupe
        $mcps = $this->em->getRepository(MatiereClasseProf::class)
            ->findBy(['groupe' => $groupe]);

        $matieresData = [];

        foreach ($mcps as $mcp) {
            $matiere = $mcp->getMatiere();
            if (!$matiere) {
                continue;
            }

            $matiereId = $matiere->getId();
            if (!isset($matieresData[$matiereId])) {
                $matieresData[$matiereId] = [
                    'id'   => $matiere->getId(),
                    'nom_fr'  => $matiere->getNomFr(),
                    'nom_ar'  => $matiere->getNomAr(),
                    'profs' => []
                ];
            }

            $enseignant = $mcp->getEnseignant();
            if ($enseignant) {
                $matieresData[$matiereId]['profs'][] = [
                    'id'        => $enseignant->getId(),
                    'nom'       => $enseignant->getNomFr(),
                    'prenom'    => $enseignant->getPrenomFr(),
                    'principal' => $mcp->isPrincipal() ? 1 : 0,
                ];
            }
        }

        // Si une matière n’a aucun prof, renvoyer prof = null
        foreach ($matieresData as &$matiere) {
            if (empty($matiere['profs'])) {
                $matiere['profs'] = null;
            }
        }

        return $this->json([
            'groupe' => [
                'id'     => $groupe->getId(),
                'nom_ar'    => $groupe->getNomAr(),
                'nom_fr'    => $groupe->getNomFr(),
                'niveau_fr' => $groupe->getNiveau() ? $groupe->getNiveau()->getNomFr() : null,
                'niveau_ar' => $groupe->getNiveau() ? $groupe->getNiveau()->getNomAr() : null,
            ],
            'matieres' => array_values($matieresData)
        ]);
    }

    #[Route('/{id}/create-minis', name: 'groupe_create_minis', methods: ['GET'])]
    public function createMinis(int $id): JsonResponse
    {
        $groupe = $this->em->getRepository(Groupe::class)->find($id);
        if (!$groupe) {
            return $this->json(['error' => 'Groupe introuvable'], 404);
        }

        // Vérifier si les minis existent déjà
        if (count($groupe->getGroupeMinis()) > 0) {
            return $this->json(['error' => 'Des sous-groupes existent déjà pour ce groupe'], 400);
        }

        $suffixes = [
            ['fr' => 'A', 'ar' => 'أ'],
            ['fr' => 'B', 'ar' => 'ب'],
        ];

        foreach ($suffixes as $s) {
            $mini = new GroupeMini();
            $mini->setNomFr(trim($groupe->getNomFr() . ' ' . $s['fr']));
            $mini->setNomAr(trim($groupe->getNomAr() . ' ' . $s['ar']));
            $mini->setGroupe($groupe);
            $this->em->persist($mini);
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Deux sous-groupes créés avec succès',
            'groupe' => $groupe->getId(),
            'minis' => array_map(
                fn(GroupeMini $gm) => [
                    'id' => $gm->getId(),
                    'nom_fr' => $gm->getNomFr(),
                    'nom_ar' => $gm->getNomAr(),
                ],
                $groupe->getGroupeMinis()->toArray()
            ),
        ]);
    }

    #[Route('/{id}/split-eleves', name: 'groupe_split_eleves', methods: ['GET'])]
    public function splitEleves(int $id): JsonResponse
    {
        $groupe = $this->em->getRepository(Groupe::class)->find($id);
        if (!$groupe) {
            return $this->json(['error' => 'Groupe introuvable'], 404);
        }

        $minis = $groupe->getGroupeMinis()->toArray();
        if (count($minis) < 2) {
            return $this->json(['error' => 'Ce groupe doit d’abord avoir deux sous-groupes (A et B)'], 400);
        }

        [$miniA, $miniB] = $minis;

        // Vérifier si un mini-groupe contient déjà des élèves
        $hasElevesA = count($miniA->getEleves()) > 0;
        $hasElevesB = count($miniB->getEleves()) > 0;
        if ($hasElevesA || $hasElevesB) {
            return $this->json([
                'error' => 'Impossible de diviser automatiquement : un sous-groupe contient déjà des élèves. Répartition manuelle requise.'
            ], 400);
        }


        // Récupérer les élèves triés par nom arabe
        $eleves = $this->em->getRepository(Eleve::class)
            ->createQueryBuilder('e')
            ->where('e.groupe = :g')
            ->setParameter('g', $groupe)
            ->orderBy('e.nom_ar', 'ASC')
            ->getQuery()
            ->getResult();

        $count = count($eleves);
        if ($count === 0) {
            return $this->json(['error' => 'Aucun élève trouvé dans ce groupe'], 404);
        }

        // Division en deux parties
        $half = ceil($count / 2);
        [$miniA, $miniB] = $minis;

        foreach ($eleves as $i => $eleve) {
            $eleve->setGroupeMini($i < $half ? $miniA : $miniB);
        }

        $this->em->flush();

        return $this->json([
            'message' => "Les élèves ont été répartis entre les sous-groupes A et B.",
            'total' => $count,
            'miniA' => [
                'id' => $miniA->getId(),
                'nom_fr' => $miniA->getNomFr(),
                'count' => count(array_filter($eleves, fn($e) => $e->getGroupeMini() === $miniA))
            ],
            'miniB' => [
                'id' => $miniB->getId(),
                'nom_fr' => $miniB->getNomFr(),
                'count' => count(array_filter($eleves, fn($e) => $e->getGroupeMini() === $miniB))
            ],
        ]);
    }


    #[Route('/{id}/assign-eleves', name: 'groupe_assign_eleves', methods: ['POST'])]
    public function assignEleves(int $id, Request $request): JsonResponse
    {
        $groupe = $this->em->getRepository(Groupe::class)->find($id);
        if (!$groupe) {
            return $this->json(['error' => 'Groupe introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['mini_id'], $data['eleve_ids']) || !is_array($data['eleve_ids'])) {
            return $this->json(['error' => 'Requête invalide. Paramètres attendus : mini_id, eleve_ids[]'], 400);
        }

        $mini = $this->em->getRepository(GroupeMini::class)->find($data['mini_id']);
        if (!$mini) {
            return $this->json(['error' => 'Mini-groupe introuvable'], 404);
        }

        // Vérifier que le mini-groupe appartient bien à ce groupe
        if ($mini->getGroupe()->getId() !== $groupe->getId()) {
            return $this->json(['error' => 'Ce mini-groupe n’appartient pas au groupe spécifié'], 400);
        }

        $elevesRepo = $this->em->getRepository(Eleve::class);
        $affected = [];
        $skipped = [];

        foreach ($data['eleve_ids'] as $eleveId) {
            $eleve = $elevesRepo->find($eleveId);
            if (!$eleve) {
                $skipped[] = ['id' => $eleveId, 'reason' => 'Élève introuvable'];
                continue;
            }

            // Vérifier que l’élève est bien dans ce groupe
            if (!$eleve->getGroupe() || $eleve->getGroupe()->getId() !== $groupe->getId()) {
                $skipped[] = ['id' => $eleveId, 'reason' => 'Élève n’appartient pas à ce groupe'];
                continue;
            }

            // Vérifier s’il est déjà affecté à un mini-groupe du même groupe
            $currentMini = $eleve->getGroupeMini();
            if ($currentMini && $currentMini->getGroupe()->getId() === $groupe->getId()) {
                $skipped[] = ['id' => $eleveId, 'reason' => 'Déjà affecté à un mini-groupe de ce groupe'];
                continue;
            }

            $eleve->setGroupeMini($mini);
            $affected[] = $eleveId;
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Affectation terminée',
            'mini_groupe' => [
                'id' => $mini->getId(),
                'nom_fr' => $mini->getNomFr(),
                'nom_ar' => $mini->getNomAr(),
            ],
            'affectes' => $affected,
            'ignores' => $skipped,
        ]);
    }



}
