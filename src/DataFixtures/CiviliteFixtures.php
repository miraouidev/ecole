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
        $civiliteM = new Civilite();
        $civiliteM->setNom('M.');
        $civiliteM->setCode('M');
        $manager->persist($civiliteM);
        $this->addReference(self::CIVILITE_M, $civiliteM);

        $civiliteMme = new Civilite();
        $civiliteMme->setNom('Mme');
        $civiliteMme->setCode('MME');
        $manager->persist($civiliteMme);
        $this->addReference(self::CIVILITE_MME, $civiliteMme);

        $manager->flush();
    }
}
