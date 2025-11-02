<?php

namespace App\DataFixtures;

use App\Entity\AnneeScolaireCourante;
use App\Entity\Semestre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnneeScolaireCouranteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();
        $year = (int) $now->format('Y');

        // Si on est avant août, on recule d'une année scolaire
        if ((int) $now->format('n') < 8) {
            $year -= 1;
        }

        $dateDebut = new \DateTime("$year-08-01");
        $dateFin   = new \DateTime(($year + 1) . "-07-31");

        // --- Création de l'année scolaire courante ---
        $annee = new AnneeScolaireCourante();
        $annee->setNom($year . '-' . ($year + 1));
        $annee->setDateDebut($dateDebut);
        $annee->setDateFin($dateFin);
        $annee->setIsActive(true);

        $manager->persist($annee);

        // --- Création des 3 semestres ---
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
            if($data['number'] === 1) {
                $semestre->setIsRemplie(true); /// possible remplie les notes
                $semestre->setIsAffiche(true); /// possible affiche les notes  pour parents et etudiants et profs
            } else {
                $semestre->setIsRemplie(false); /// possible remplie les notes
                $semestre->setIsAffiche(false); /// possible affiche les notes  pour parents et etudiants et profs            
            }   
            $manager->persist($semestre);
        }

        $manager->flush();
    }
}
