<?php

namespace App\DataFixtures;

use App\Entity\Configuration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigurationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Check if configuration already exists
        $existing = $manager->getRepository(Configuration::class)->findAll();
        if (count($existing) === 0) {
            $config = new Configuration();
            $config->setIsModifierTypeNote(false);
            $manager->persist($config);
            $manager->flush();
        }
    }
}
