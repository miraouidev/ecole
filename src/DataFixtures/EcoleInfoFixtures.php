<?php

namespace App\DataFixtures;

use App\Entity\EcoleInfo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class EcoleInfoFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $ecole = (new EcoleInfo())
            ->setNomAr('الأغألبة')
            ->setNpmFr('Alaghaliba')
            ->setAdresseAr('شارع محمد علي القيروان')
            ->setAdresseFr('Rue Mohamed Ali Kairouan')
            ->setPhone('22113355')
            ->setResponsableAr('هشام خلفون')
            ->setResponsableFr('Hichem Khalfoun');

        $manager->persist($ecole);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['ecoleinfo'];
    }
}
