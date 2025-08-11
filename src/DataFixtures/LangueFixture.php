<?php

namespace App\DataFixtures;

use App\Entity\Langue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LangueFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $languages = [
            ['code' => 'ar', 'name' => 'Arabe'],
            ['code' => 'fr', 'name' => 'Français'],
            ['code' => 'en', 'name' => 'English'],
        ];

        foreach ($languages as $lang) {
            $entity = new Langue();
            $entity->setCode($lang['code']);
            $entity->setName($lang['name']);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
