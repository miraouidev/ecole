<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Groupe;
use App\Entity\MatiereClasseProf;
use App\Entity\Matieres;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/api/scolaire/matiere-classe-prof')]
class MatiereClasseProfController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private CacheInterface $cache) {}

    private function getIdFromIri(string $iri): ?int
    {
        return preg_match('#/(\d+)$#', $iri, $matches) ? (int)$matches[1] : null;
    }

    /**
     * ➕ Ajouter une matière/enseignant à un ou plusieurs groupes
     */
    #[Route('/add', name: 'matiere_classe_prof_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $enseignantId = $this->getIdFromIri($data['enseignant'] ?? '');
        $matiereId    = $this->getIdFromIri($data['matiere'] ?? '');
        $groupeIris   = $data['groupes'] ?? [];
        $principal    = $data['principal'] ?? false;

        if ( !$matiereId || empty($groupeIris)) {
            return $this->json(['error' => 'matière et groupes requis'], 400);
        }

        $enseignant = $this->em->getRepository(Enseignant::class)->find($enseignantId??0);
        $matiere    = $this->em->getRepository(Matieres::class)->find($matiereId);

        if (!$matiere) {
            return $this->json(['error' => 'Matière introuvable'], 404);
        }

        $created = [];
        foreach ($groupeIris as $groupeIri) {
            $groupeId = $this->getIdFromIri($groupeIri);
            if (!$groupeId) continue;

            $groupe = $this->em->getRepository(Groupe::class)->find($groupeId);
            if (!$groupe) continue;

            $mcp = new MatiereClasseProf();
            $mcp->setEnseignant($enseignant);
            $mcp->setMatiere($matiere);
            $mcp->setGroupe($groupe);
            $mcp->setPrincipal((bool)$principal);
            if ($principal && $enseignant) {
                // Désactiver les anciens "principal"
                $this->em->createQueryBuilder()
                    ->update(MatiereClasseProf::class, 'm')
                    ->set('m.principal', ':false')
                    ->where('m.groupe = :groupe')
                    ->andWhere('m.matiere = :matiere')
                    ->setParameter('false', false)
                    ->setParameter('groupe', $groupe)
                    ->setParameter('matiere', $matiere)
                    ->getQuery()
                    ->execute();
            }
            $this->em->persist($mcp);
            $created[] = $mcp;
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Matière/Prof ajoutés avec succès',
            'count'   => count($created),
        ]);
    }

    /**
     * ✏️ Mettre à jour un enregistrement (principal true/false)
     */
    #[Route('/update', name: 'matiere_classe_prof_update', methods: ['PATCH'])]
    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $mcpId = $this->getIdFromIri($data['mcp'] ?? '');
        if (!$mcpId) {
            return $this->json(['error' => 'IRI du matiere_classe_prof requis'], 400);
        }

        $mcp = $this->em->getRepository(MatiereClasseProf::class)->find($mcpId);
        if (!$mcp) {
            return $this->json(['error' => 'MatiereClasseProf introuvable'], 404);
        }

        if (isset($data['principal']) && $data['principal'] === true) {
            // Mettre les autres à false
            $this->em->createQueryBuilder()
                ->update(MatiereClasseProf::class, 'm')
                ->set('m.principal', ':false')
                ->where('m.groupe = :groupe')
                ->andWhere('m.matiere = :matiere')
                ->andWhere('m != :current')
                ->setParameter('false', false)
                ->setParameter('groupe', $mcp->getGroupe())
                ->setParameter('matiere', $mcp->getMatiere())
                ->setParameter('current', $mcp)
                ->getQuery()
                ->execute();
        }
        $mcp->setPrincipal((bool)$data['principal']);

        $this->em->flush();

        return $this->json(['message' => 'MatiereClasseProf mis à jour']);
    }


        /**
     * 🔄 Remplacer un enseignant (oldProf) par un autre (newProf) pour une matière dans des groupes
     */
    #[Route('/replace-prof', name: 'matiere_classe_prof_replace_prof', methods: ['PATCH'])]
    public function replaceProf(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $oldProfId = $this->getIdFromIri($data['oldProf'] ?? '');
        $newProfId = $this->getIdFromIri($data['newProf'] ?? '');
        $matiereId = $this->getIdFromIri($data['matiere'] ?? '');
        $groupeIris = $data['groupes'] ?? [];

        if (!$oldProfId || !$newProfId || !$matiereId || empty($groupeIris)) {
            return $this->json(['error' => 'oldProf, newProf, matière et groupes requis'], 400);
        }

        $oldProf = $this->em->getRepository(Enseignant::class)->find($oldProfId);
        $newProf = $this->em->getRepository(Enseignant::class)->find($newProfId);
        $matiere = $this->em->getRepository(Matieres::class)->find($matiereId);

        if (!$oldProf || !$newProf || !$matiere) {
            return $this->json(['error' => 'Professeur ou matière introuvable'], 404);
        }

        $updated = 0;
        foreach ($groupeIris as $groupeIri) {
            $groupeId = $this->getIdFromIri($groupeIri);
            if (!$groupeId) continue;

            $groupe = $this->em->getRepository(Groupe::class)->find($groupeId);
            if (!$groupe) continue;

            // Chercher l’association existante avec l’ancien prof
            $mcp = $this->em->getRepository(MatiereClasseProf::class)->findOneBy([
                'enseignant' => $oldProf,
                'matiere'    => $matiere,
                'groupe'     => $groupe,
            ]);

            if ($mcp) {
                $mcp->setEnseignant($newProf);
                $updated++;
            }
        }

        $this->em->flush();

        return $this->json([
            'message' => "Remplacement effectué avec succès",
            'count'   => $updated
        ]);
    }

        /**
     * 🌟 Définir un prof comme principal (ou non)
     */
    #[Route('/set-principal', name: 'matiere_classe_prof_set_principal', methods: ['PATCH'])]
    public function setPrincipal(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $profId = $this->getIdFromIri($data['prof'] ?? '');
        $principal = $data['principal'] ?? null;

        if (!$profId || !is_bool($principal)) {
            return $this->json(['error' => 'Paramètres requis: prof (IRI) et principal (bool)'], 400);
        }

        $prof = $this->em->getRepository(Enseignant::class)->find($profId);
        if (!$prof) {
            return $this->json(['error' => 'Professeur introuvable'], 404);
        }

        // Tous les MCP liés à ce prof
        $mcps = $this->em->getRepository(MatiereClasseProf::class)->findBy(['enseignant' => $prof]);

        if (empty($mcps)) {
            return $this->json(['error' => 'Aucun enregistrement trouvé pour ce prof'], 404);
        }

        foreach ($mcps as $mcp) {
            if ($principal === true) {
                // Mettre les autres profs du même groupe+matière à false
                $this->em->createQueryBuilder()
                    ->update(MatiereClasseProf::class, 'm')
                    ->set('m.principal', ':false')
                    ->where('m.groupe = :groupe')
                    ->andWhere('m.matiere = :matiere')
                    ->setParameter('false', false)
                    ->setParameter('groupe', $mcp->getGroupe())
                    ->setParameter('matiere', $mcp->getMatiere())
                    ->getQuery()
                    ->execute();

                // Mettre ce prof en principal
                $mcp->setPrincipal(true);

            } else {
                // Seulement ce prof passe en false
                $mcp->setPrincipal(false);
            }
        }

        $this->em->flush();

        return $this->json([
            'message' => $principal ? 'Prof défini comme principal' : 'Prof retiré de principal',
        ]);
    }

    /// api get 
    /**
     * 📌 Récupérer les infos d’un prof : groupes + matières
     */
    #[Route('/by-prof/{id}', name: 'matiere_classe_prof_by_prof', methods: ['GET'])]
    public function getByProf(int $id): JsonResponse
    {
        $cacheKey = "matiere_prof_$id";

        $result = $this->cache->get($cacheKey, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(3600); // ⏱️ cache 1h

            $prof = $this->em->getRepository(Enseignant::class)->find($id);
            if (!$prof) {
                return null;
            }

            $mcps = $this->em->getRepository(MatiereClasseProf::class)->findBy(['enseignant' => $prof]);

            $groupes = [];
            $matieres = [];

            foreach ($mcps as $mcp) {
                $groupe = $mcp->getGroupe();
                $matiere = $mcp->getMatiere();

                if ($groupe) {
                    $groupes[$groupe->getId()] = [
                        'id'   => $groupe->getId(),
                        'nom_ar'  => $groupe->getNomAr() ?? "Groupe {$groupe->getId()}",
                        'nom_fr'  => $groupe->getNomFr() ?? "Groupe {$groupe->getId()}",

                    ];
                }

                if ($matiere) {
                    $matieres[$matiere->getId()] = [
                        'id'   => $matiere->getId(),
                        'nom_ar'  => $matiere->getNomAr() ?? "Groupe {$groupe->getId()}",
                        'nom_fr'  => $matiere->getNomFr() ?? "Groupe {$groupe->getId()}",
                    ];
                }
            }

            return [
                'prof' => [
                    'id'       => $prof->getId(),
                    'nom'      => $prof->getNomFr(),
                    'prenom'   => $prof->getPrenomFr(),
                    'cin'      => $prof->getCin(),
                    'mobile'   => $prof->getMobile(),
                ],
                'groupes'  => array_values($groupes),
                'matieres' => array_values($matieres),
            ];
        });

        if ($result === null) {
            return $this->json(['error' => 'Prof introuvable'], 404);
        }

        return $this->json($result);
    }

}
