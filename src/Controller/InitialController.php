<?php

namespace App\Controller;

use App\Entity\AnneeScolaireCourante;
use App\Entity\Configuration;
use App\Entity\Matieres;
use App\Entity\TypeNote;
use App\Entity\MatieresTypeNote;
use App\Entity\Semestre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/configuration/initail')]
class InitialController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/matieres-type-note', name: 'init_matieres_type_note', methods: ['POST'])]
    public function initializeMatieresTypeNote(Request $request): JsonResponse
    {

        // Check configuration
        $config = $this->em->getRepository(Configuration::class)->findOneBy([]);
        if (!$config || !$config->isModifierTypeNote()) {
            return new JsonResponse(['alert' => 'Merci d’activer TypeNote dans la configuration'], 400);
        }


        // Get semestre id from request
        $data = json_decode($request->getContent(), true);
        $semestreId = $data['semestre_id'] ?? null;
        if (!$semestreId) {
            return new JsonResponse(['error' => 'Semestre ID is required'], 400);
        }

        // Get active academic year
        $annee = $this->em->getRepository(AnneeScolaireCourante::class)
            ->findOneBy(['isActive' => true]);

        if (!$annee) {
            return new JsonResponse(['alert' => 'Aucune année scolaire active trouvée'], 400);
        }

        // Get semestre
        $semestre = $this->em->getRepository(Semestre::class)->find($semestreId);
        if (!$semestre) {
            return new JsonResponse(['error' => 'Semestre introuvable'], 404);
        }

        // Get all Matieres and TypeNotes
        $matieres = $this->em->getRepository(Matieres::class)->findAll();
        $types = $this->em->getRepository(TypeNote::class)->findBy(['forAll' => true]);

        if (empty($matieres) || empty($types)) {
            return new JsonResponse(['alert' => 'Aucune matière ou type de note trouvé'], 400);
        }

        // Create MatieresTypeNote for each combination if not exists
        $repo = $this->em->getRepository(MatieresTypeNote::class);
        $count = 0;

        foreach ($matieres as $matiere) {
            foreach ($types as $type) {
                $exists = $repo->findOneBy([
                    'matiere' => $matiere,
                    'typeNote' => $type,
                    'semestre' => $semestre,
                ]);

                if (!$exists) {
                    $relation = new MatieresTypeNote();
                    $relation->setMatiere($matiere)
                        ->setTypeNote($type)
                        ->setSemestre($semestre)
                        ->setCoefficient(0.5);
                    $this->em->persist($relation);
                    $count++;
                }
            }
        }

        $this->em->flush();

        return new JsonResponse([
            'success' => true,
            'created' => $count,
            'annee' => $annee->getNom(),
            'semestre' => $semestre->getNomFr(),
        ]);
    }



    /**
     * Create new academic year if missing (auto with semestres)
     */
    #[Route('/annee', name: 'init_annee_scolaire', methods: ['POST'])]
    public function createAnneeScolaire(): JsonResponse
    {
        $now = new \DateTime();
        $year = (int) $now->format('Y');

        if ((int) $now->format('n') < 8) {
            $year -= 1;
        }

        $nom = sprintf('%d-%d', $year, $year + 1);
        $dateDebut = new \DateTime("$year-08-01");
        $dateFin = new \DateTime(($year + 1) . "-07-31");

        $existing = $this->em->getRepository(AnneeScolaireCourante::class)
            ->findOneBy(['nom' => $nom]);
        if ($existing) {
            return new JsonResponse([
                'info' => "L'année scolaire $nom existe déjà.",
                'annee_id' => $existing->getId(),
            ]);
        }

        // Désactiver toutes les anciennes années
        $this->em->createQuery('UPDATE App\Entity\AnneeScolaireCourante a SET a.isActive = false')
            ->execute();

        // Créer la nouvelle année
        $annee = new AnneeScolaireCourante();
        $annee->setNom($nom);
        $annee->setDateDebut($dateDebut);
        $annee->setDateFin($dateFin);
        $annee->setIsActive(true);
        $this->em->persist($annee);

        // Créer 3 semestres
        $semestresData = [
            ['number' => 1, 'nom_fr' => 'Premier Semestre', 'nom_ar' => 'الفصل الأول'],
            ['number' => 2, 'nom_fr' => 'Deuxième Semestre', 'nom_ar' => 'الفصل الثاني'],
            ['number' => 3, 'nom_fr' => 'Troisième Semestre', 'nom_ar' => 'الفصل الثالث'],
        ];

        foreach ($semestresData as $data) {
            $semestre = new Semestre();
            $semestre->setNumber($data['number']);
            $semestre->setNomFr($data['nom_fr']);
            $semestre->setNomAr($data['nom_ar']);
            $semestre->setAnneeScolaire($annee);

            // Seul le premier semestre est actif
            if ($data['number'] === 1) {
                $semestre->setIsRemplie(true);
                $semestre->setIsAffiche(true);
            } else {
                $semestre->setIsRemplie(false);
                $semestre->setIsAffiche(false);
            }

            $this->em->persist($semestre);
        }

        $this->em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => "Nouvelle année scolaire $nom créée avec succès.",
            'annee' => $nom,
        ]);
    }
    
}
