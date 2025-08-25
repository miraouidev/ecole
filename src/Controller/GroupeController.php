<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\MatiereClasseProf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}
