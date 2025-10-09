<?php

namespace App\DataFixtures;

use App\Entity\RhJourFerie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RhJourFerieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $joursFeries = [
            // --- Année 2025 ---
            ['2025-01-01', 'رأس السنة الميلادية', 'Nouvel An', true],
            ['2025-03-20', 'عيد الاستقلال', 'Fête de l’Indépendance', true],
            ['2025-04-09', 'عيد الشهداء', 'Fête des Martyrs', true],
            ['2025-05-01', 'عيد الشغل', 'Fête du Travail', true],
            ['2025-06-27', 'عيد الأضحى', 'Aïd El-Idha', true],
            ['2025-06-28', 'عيد الأضحى (اليوم الثاني)', 'Aïd El-Idha (2e jour)', true],
            ['2025-06-01', 'رأس السنة الهجرية', 'Nouvel An Hégirien', true],
            ['2025-07-25', 'عيد الجمهورية', 'Fête de la République', true],
            ['2025-08-14', 'عيد المولد النبوي', 'Mouled', true],
            ['2025-10-15', 'عيد الجلاء', 'Fête de l’Évacuation', true],

            // --- Année 2026 ---
            ['2026-01-01', 'رأس السنة الميلادية', 'Nouvel An', true],
            ['2026-03-20', 'عيد الاستقلال', 'Fête de l’Indépendance', true],
            ['2026-04-09', 'عيد الشهداء', 'Fête des Martyrs', true],
            ['2026-05-01', 'عيد الشغل', 'Fête du Travail', true],
            ['2026-06-17', 'عيد الأضحى', 'Aïd El-Idha', true],
            ['2026-06-18', 'عيد الأضحى (اليوم الثاني)', 'Aïd El-Idha (2e jour)', true],
            ['2026-05-20', 'رأس السنة الهجرية', 'Nouvel An Hégirien', true],
            ['2026-07-25', 'عيد الجمهورية', 'Fête de la République', true],
            ['2026-09-02', 'عيد المولد النبوي', 'Mouled', true],
            ['2026-10-15', 'عيد الجلاء', 'Fête de l’Évacuation', true],
        ];

        foreach ($joursFeries as [$date, $libelleAr, $libelleFr, $paye]) {
            $jourFerie = new RhJourFerie();
            $jourFerie->setDate(new \DateTimeImmutable($date));
            $jourFerie->setLibelleAr($libelleAr);
            $jourFerie->setLibelleFr($libelleFr);
            $jourFerie->setPaye($paye);
            $manager->persist($jourFerie);
        }

        $manager->flush();
    }
}
