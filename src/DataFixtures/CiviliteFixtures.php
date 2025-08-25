<?php

namespace App\DataFixtures;

use App\Entity\Civilite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CiviliteFixtures extends Fixture
{
    public const CIVILITE_M = 'civilite_m';
    public const CIVILITE_MME = 'civilite_mme';

    public function load(ObjectManager $manager): void
    {
        // Monsieur
        $civiliteM = new Civilite();
        $civiliteM->setNomFr('M.');
        $civiliteM->setNomAr('السيد'); // Monsieur en arabe
        $civiliteM->setCode('M');
        $manager->persist($civiliteM);
        $this->addReference(self::CIVILITE_M, $civiliteM);

        // Madame
        $civiliteMme = new Civilite();
        $civiliteMme->setNomFr('Mme');
        $civiliteMme->setNomAr('السيدة'); // Madame en arabe
        $civiliteMme->setCode('MME');
        $manager->persist($civiliteMme);
        $this->addReference(self::CIVILITE_MME, $civiliteMme);

        $manager->flush();
    }
}
