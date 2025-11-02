<?php

namespace App\DataFixtures;

use App\Entity\TypeNote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeNoteFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            ['fr' => 'Devoir de contrôle 1', 'ar' => 'فرض مراقبة 1','active' => true],
            ['fr' => 'Devoir de contrôle 2', 'ar' => 'فرض مراقبة 2','active' => false],
            ['fr' => 'Devoir de synthèse',   'ar' => 'فرض تأليفي','active' => true],
            ['fr' => 'Orale',                'ar' => 'شفوي','active' => true],
            ['fr' => 'Test',                 'ar' => 'اختبار','active' => false],
            ['fr' => 'Participation',        'ar' => 'مشاركة','active' => false],
        ];

        foreach ($types as $data) {
            $typeNote = new TypeNote();
            $typeNote->setNomFr($data['fr']);
            $typeNote->setNomAr($data['ar']);
            $typeNote->setForAll($data['active']);
            $manager->persist($typeNote);
        }
        $manager->flush();
    }
}

