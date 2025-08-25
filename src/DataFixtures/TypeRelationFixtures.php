<?php

namespace App\DataFixtures;

use App\Entity\TypeRelation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeRelationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $relations = [
            ['fr' => 'Père',     'ar' => 'الأب',      'code' => 'PERE'],
            ['fr' => 'Mère',     'ar' => 'الأم',      'code' => 'MERE'],
            ['fr' => 'Frère',    'ar' => 'الأخ',      'code' => 'FRERE'],
            ['fr' => 'Sœur',     'ar' => 'الأخت',     'code' => 'SOEUR'],
            ['fr' => 'Oncle',    'ar' => 'العم',      'code' => 'ONCLE'],
            ['fr' => 'Tante',    'ar' => 'العمة',     'code' => 'TANTE']
        ];

        foreach ($relations as $rel) {
            $entity = new TypeRelation();
            $entity->setNomFr($rel['fr']);
            $entity->setNomAr($rel['ar']);
            $entity->setCode($rel['code']);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
