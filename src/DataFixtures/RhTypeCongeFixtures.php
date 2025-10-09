<?php

namespace App\DataFixtures;

use App\Entity\RhTypeConge;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RhTypeCongeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typesConges = [
            // Congés standards
            ['libelle_fr' => 'Congé annuel validé',   'libelle_ar' => 'عطلة سنوية مصادق عليها', 'coleur' => '#28a745'],
            ['libelle_fr' => 'Congé annuel non validé','libelle_ar' => 'عطلة سنوية غير مصادق عليها', 'coleur' => '#ffc107'],

            // Maladie
            ['libelle_fr' => 'Congé maladie',          'libelle_ar' => 'عطلة مرضية', 'coleur' => '#17a2b8'],

            // Mariage
            ['libelle_fr' => 'Congé mariage',          'libelle_ar' => 'عطلة زواج', 'coleur' => '#007bff'],

            // Niveaux mariage (exemples spécifiques)
            ['libelle_fr' => 'Mariage niveau 1',       'libelle_ar' => 'زواج - المستوى 1', 'coleur' => '#6f42c1'],
            ['libelle_fr' => 'Mariage niveau 2',       'libelle_ar' => 'زواج - المستوى 2', 'coleur' => '#6610f2'],

            // Autres types courants
            ['libelle_fr' => 'Congé maternité',        'libelle_ar' => 'عطلة أمومة', 'coleur' => '#e83e8c'],
            ['libelle_fr' => 'Congé paternité',        'libelle_ar' => 'عطلة أبوة', 'coleur' => '#fd7e14'],
            ['libelle_fr' => 'Absence injustifiée',    'libelle_ar' => 'غياب غير مبرر', 'coleur' => '#dc3545'],
            ['libelle_fr' => 'Absence autorisée',      'libelle_ar' => 'غياب مبرر', 'coleur' => '#20c997'],
            ['libelle_fr' => 'Congé exceptionnel',     'libelle_ar' => 'عطلة استثنائية', 'coleur' => '#0dcaf0'],
            ['libelle_fr' => 'Congé décès',            'libelle_ar' => 'عطلة وفاة', 'coleur' => '#343a40'],
            ['libelle_fr' => 'Congé mission',          'libelle_ar' => 'عطلة مهمة', 'coleur' => '#198754']
        ];

        foreach ($typesConges as $type) {
            $conge = new RhTypeConge();
            $conge->setLibelleFr($type['libelle_fr']);
            $conge->setLibelleAr($type['libelle_ar']);
            $conge->setColeur($type['coleur']);
            $manager->persist($conge);
        }

        $manager->flush();
    }
}
