<?php

namespace App\DataFixtures;

use App\Entity\Niveau;
use App\Entity\Matieres;
use App\Entity\NiveauMatiere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NiveauMatiereFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // All matières (global list)
        $allMatieres = [
            ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
            ['fr' => 'Sciences naturelles', 'ar' => 'علوم طبيعية'],
            ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
            ['fr' => 'Français', 'ar' => 'فرنسية'],
            ['fr' => 'Arabe', 'ar' => 'عربية'],
            ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
            ['fr' => 'Histoire et Géographie', 'ar' => 'التاريخ والجغرافيا'],
            ['fr' => 'Géographie', 'ar' => 'جغرافيا'],
            ['fr' => 'Éducation Islamique', 'ar' => 'تربية إسلامية'],
            ['fr' => 'Éducation Civique', 'ar' => 'تربية مدنية'],
            ['fr' => 'Éducation Artistique', 'ar' => 'تربية فنية'],
            ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
            ['fr' => 'Informatique', 'ar' => 'إعلامية'],
            ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
            ['fr' => 'Technologie', 'ar' => 'تكنولوجيا'],
            ['fr' => 'Économie & Gestion', 'ar' => 'اقتصاد و تصرف'],
            ['fr' => 'Mécanique', 'ar' => 'ميكانيك'],
            ['fr' => 'Électricité', 'ar' => 'كهرباء'],
        ];

        // All niveaux
        $niveaux = [
            '7EME' => ['fr' => '7ème année', 'ar' => 'السنة السابعة'],
            '8EME' => ['fr' => '8ème année', 'ar' => 'السنة الثامنة'],
            '9EME' => ['fr' => '9ème année', 'ar' => 'السنة التاسعة'],
            '1SEC' => ['fr' => '1ère année secondaire', 'ar' => 'السنة الأولى ثانوي'],
            '2SEC-MATH' => ['fr' => '2ème année secondaire - Mathématiques', 'ar' => 'السنة الثانية ثانوي - رياضيات'],
            '2SEC-SCI' => ['fr' => '2ème année secondaire - Sciences', 'ar' => 'السنة الثانية ثانوي - علوم'],
            '2SEC-LET' => ['fr' => '2ème année secondaire - Lettres', 'ar' => 'السنة الثانية ثانوي - آداب'],
            '2SEC-INFO' => ['fr' => '2ème année secondaire - Informatique', 'ar' => 'السنة الثانية ثانوي - إعلامية'],
            '2SEC-ECO' => ['fr' => '2ème année secondaire - Économie & Gestion', 'ar' => 'السنة الثانية ثانوي - اقتصاد و تصرف'],
            '3SEC-MATH' => ['fr' => '3ème année secondaire - Mathématiques', 'ar' => 'السنة الثالثة ثانوي - رياضيات'],
            '3SEC-SCI' => ['fr' => '3ème année secondaire - Sciences', 'ar' => 'السنة الثالثة ثانوي - علوم'],
            '3SEC-LET' => ['fr' => '3ème année secondaire - Lettres', 'ar' => 'السنة الثالثة ثانوي - آداب'],
            '3SEC-INFO' => ['fr' => '3ème année secondaire - Informatique', 'ar' => 'السنة الثالثة ثانوي - إعلامية'],
            '3SEC-ECO' => ['fr' => '3ème année secondaire - Économie & Gestion', 'ar' => 'السنة الثالثة ثانوي - اقتصاد و تصرف'],
            'BAC-MATH' => ['fr' => 'Baccalauréat - Mathématiques', 'ar' => 'البكالوريا - رياضيات'],
            'BAC-SCI' => ['fr' => 'Baccalauréat - Sciences expérimentales', 'ar' => 'البكالوريا - علوم تجريبية'],
            'BAC-LET' => ['fr' => 'Baccalauréat - Lettres', 'ar' => 'البكالوريا - آداب'],
            'BAC-INFO' => ['fr' => 'Baccalauréat - Informatique', 'ar' => 'البكالوريا - إعلامية'],
            'BAC-ECO' => ['fr' => 'Baccalauréat - Économie & Gestion', 'ar' => 'البكالوريا - اقتصاد و تصرف'],
            'BAC-TECH' => ['fr' => 'Baccalauréat - Technique', 'ar' => 'البكالوريا - تقني'],
        ];

        // Create all unique matières
        $matiereCache = [];
        foreach ($allMatieres as $matiereData) {
            $matiere = new Matieres();
            $matiere->setNomFr($matiereData['fr']);
            $matiere->setNomAr($matiereData['ar']);
            $matiere->setIsActive(true);
            $manager->persist($matiere);
            $matiereCache[$matiereData['fr']] = $matiere;
        }

        // Create all niveaux and link every matiere
        foreach ($niveaux as $code => $data) {
            $niveau = new Niveau();
            $niveau->setCode($code);
            $niveau->setNomFr($data['fr']);
            $niveau->setNomAr($data['ar']);
            $niveau->setIsActive(true);
            $manager->persist($niveau);

            foreach ($matiereCache as $matiere) {
                $link = new NiveauMatiere();
                $link->setNiveau($niveau);
                $link->setMatiere($matiere);
                $link->setIsActive(true);
                $manager->persist($link);
            }
        }

        $manager->flush();
    }
}
