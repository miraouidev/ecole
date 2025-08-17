<?php

namespace App\DataFixtures;

use App\Entity\Civilite;
use App\Entity\Enseignant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EnseignantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // French locale for dates & phone formatting

        // Make sure we have civilités
        $civiliteM = new Civilite();
        $civiliteM->setNom("M.")
                  ->setCode("M");
        $manager->persist($civiliteM);

        $civiliteMme = new Civilite();
        $civiliteMme->setNom("Mme")
                    ->setCode("MME");
        $manager->persist($civiliteMme);

        $arabicFirstNames = [
            "Mohamed", "Sayed", "Adam", "Hassan", "Khaled",
            "Omar", "Youssef", "Ahmed", "Ibrahim", "Ali"
        ];

        $arabicLastNames = [
            "Alami", "Benali", "Haddad", "Mansour", "Salem",
            "Zahran", "Najjar", "Rahman", "Shahine", "Bakr"
        ];

        for ($i = 0; $i < 10; $i++) {
            $enseignant = new Enseignant();

            $enseignant->setCivilite($faker->randomElement([$civiliteM, $civiliteMme]));
            $enseignant->setNom($faker->randomElement($arabicLastNames));
            $enseignant->setPrenom($faker->randomElement($arabicFirstNames));
            $enseignant->setDateNai(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-50 years', '-25 years')));
            $enseignant->setStartAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 years', 'now')));
            $enseignant->setCin($faker->numerify('########')); // simple CIN format
            $enseignant->setPhone($faker->phoneNumber());
            $enseignant->setMobile($faker->phoneNumber());
            $enseignant->setAdresse($faker->address());

            $manager->persist($enseignant);
        }

        $manager->flush();
    }
}
