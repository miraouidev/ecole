<?php

namespace App\DataFixtures;

use App\Entity\Civilite;
use App\Entity\Enseignant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EnseignantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // ✅ getReference fonctionne avec UN SEUL argument maintenant
        $civiliteM = $this->getReference(CiviliteFixtures::CIVILITE_M,Civilite::class);
        $civiliteMme = $this->getReference(CiviliteFixtures::CIVILITE_MME,Civilite::class);
/*
        for ($i = 0; $i < 10; $i++) {
            $enseignant = new Enseignant();
            $enseignant->setCivilite($faker->randomElement([$civiliteM, $civiliteMme]));
            $enseignant->setNomFr("NomFr_$i");
            $enseignant->setNomAr("اسم_$i");
            $enseignant->setPrenomFr("PrenomFr_$i");
            $enseignant->setPrenomAr("إسم_$i");
            $enseignant->setDateNai(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-50 years', '-25 years')));
            $enseignant->setStartAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 years', 'now')));
            $enseignant->setCin($faker->numerify('########'));
            $enseignant->setPhone($faker->phoneNumber());
            $enseignant->setMobile($faker->phoneNumber());
            $enseignant->setAdresse($faker->address());

            $manager->persist($enseignant);
        }

        $manager->flush();
        */
    }

    public function getDependencies(): array
    {
        return [
            CiviliteFixtures::class,
        ];
    }
}
