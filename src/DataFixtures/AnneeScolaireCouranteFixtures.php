<?php

namespace App\DataFixtures;

use App\Entity\AnneeScolaireCourante;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnneeScolaireCouranteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();
        $year = (int) $now->format('Y');

        // si on est avant août, on recule d'une année scolaire
        if ((int) $now->format('n') < 8) {
            $year -= 1;
        }

        $dateDebut = new \DateTime("$year-08-01");
        $dateFin   = new \DateTime(($year + 1) . "-07-31");

        $annee = new AnneeScolaireCourante();
        $annee->setNom($year . '-' . ($year + 1));
        $annee->setDateDebut($dateDebut);
        $annee->setDateFin($dateFin);
        $annee->setIsActive(true);

        $manager->persist($annee);
        $manager->flush();
    }
}
