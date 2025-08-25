<?php

namespace App\DataFixtures;

use App\Entity\Niveau;
use App\Entity\Matieres;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NiveauMatiereFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $tabCollege=[
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Sciences naturelles', 'ar' => 'علوم طبيعية'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Histoire', 'ar' => 'تاريخ'],
                    ['fr' => 'Géographie', 'ar' => 'جغرافيا'],
                    ['fr' => 'Éducation Islamique', 'ar' => 'تربية إسلامية'],
                    ['fr' => 'Éducation Civique', 'ar' => 'تربية مدنية'],
                    ['fr' => 'Éducation Artistique', 'ar' => 'تربية فنية'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
            ];
        // 📌 Niveaux + matières associées
        $niveaux = [
            // Collège
            '7EME' => [
                'fr' => '7ème année', 'ar' => 'السنة السابعة',
                'matieres' => $tabCollege
            ],
            '8EME' => [
                'fr' => '8ème année', 'ar' => 'السنة الثامنة',
                'matieres' => $tabCollege
            ],
            '9EME' => [
                'fr' => '9ème année', 'ar' => 'السنة التاسعة',
                'matieres' => $tabCollege
            ],

            // 1ère secondaire
            '1SEC' => [
                'fr' => '1ère année secondaire', 'ar' => 'السنة الأولى ثانوي',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Sciences naturelles', 'ar' => 'علوم طبيعية'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Informatique', 'ar' => 'إعلامية'],
                    ['fr' => 'Histoire-Géographie', 'ar' => 'تاريخ-جغرافيا'],
                    ['fr' => 'Éducation Islamique', 'ar' => 'تربية إسلامية'],
                    ['fr' => 'Éducation Civique', 'ar' => 'تربية مدنية'],
                    ['fr' => 'Éducation Artistique', 'ar' => 'تربية فنية'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],

            // --- 2ème et 3ème secondaire par filière ---
            '2SEC-MATH' => [
                'fr' => '2ème année secondaire - Mathématiques', 'ar' => 'السنة الثانية ثانوي - رياضيات',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Informatique', 'ar' => 'إعلامية'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            '2SEC-SCI' => [
                'fr' => '2ème année secondaire - Sciences', 'ar' => 'السنة الثانية ثانوي - علوم',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Sciences naturelles', 'ar' => 'علوم طبيعية'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            '2SEC-LET' => [
                'fr' => '2ème année secondaire - Lettres', 'ar' => 'السنة الثانية ثانوي - آداب',
                'matieres' => [
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Histoire-Géographie', 'ar' => 'تاريخ-جغرافيا'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            '2SEC-INFO' => [
                'fr' => '2ème année secondaire - Informatique', 'ar' => 'السنة الثانية ثانوي - إعلامية',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Informatique', 'ar' => 'إعلامية'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            '2SEC-ECO' => [
                'fr' => '2ème année secondaire - Économie & Gestion', 'ar' => 'السنة الثانية ثانوي - اقتصاد و تصرف',
                'matieres' => [
                    ['fr' => 'Économie & Gestion', 'ar' => 'اقتصاد و تصرف'],
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],


                        // --- 2ème et 3ème secondaire par filière ---
            '3SEC-MATH' => [
                'fr' => '2ème année secondaire - Mathématiques', 'ar' => 'السنة الثانية ثانوي - رياضيات',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Informatique', 'ar' => 'إعلامية'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            '3SEC-SCI' => [
                'fr' => '2ème année secondaire - Sciences', 'ar' => 'السنة الثانية ثانوي - علوم',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Sciences naturelles', 'ar' => 'علوم طبيعية'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            '3SEC-LET' => [
                'fr' => '2ème année secondaire - Lettres', 'ar' => 'السنة الثانية ثانوي - آداب',
                'matieres' => [
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Histoire-Géographie', 'ar' => 'تاريخ-جغرافيا'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            '3SEC-INFO' => [
                'fr' => '2ème année secondaire - Informatique', 'ar' => 'السنة الثانية ثانوي - إعلامية',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Informatique', 'ar' => 'إعلامية'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            '3SEC-ECO' => [
                'fr' => '2ème année secondaire - Économie & Gestion', 'ar' => 'السنة الثانية ثانوي - اقتصاد و تصرف',
                'matieres' => [
                    ['fr' => 'Économie & Gestion', 'ar' => 'اقتصاد و تصرف'],
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],


            // --- BAC par filière ---
            'BAC-MATH' => [
                'fr' => 'Baccalauréat - Mathématiques', 'ar' => 'البكالوريا - رياضيات',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Informatique', 'ar' => 'إعلامية'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            'BAC-SCI' => [
                'fr' => 'Baccalauréat - Sciences expérimentales', 'ar' => 'البكالوريا - علوم تجريبية',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Sciences naturelles', 'ar' => 'علوم طبيعية'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            'BAC-LET' => [
                'fr' => 'Baccalauréat - Lettres', 'ar' => 'البكالوريا - آداب',
                'matieres' => [
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Histoire-Géographie', 'ar' => 'تاريخ-جغرافيا'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            'BAC-INFO' => [
                'fr' => 'Baccalauréat - Informatique', 'ar' => 'البكالوريا - إعلامية',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Informatique', 'ar' => 'إعلامية'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            'BAC-ECO' => [
                'fr' => 'Baccalauréat - Économie & Gestion', 'ar' => 'البكالوريا - اقتصاد و تصرف',
                'matieres' => [
                    ['fr' => 'Économie & Gestion', 'ar' => 'اقتصاد و تصرف'],
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Arabe', 'ar' => 'عربية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
            'BAC-TECH' => [
                'fr' => 'Baccalauréat - Technique', 'ar' => 'البكالوريا - تقني',
                'matieres' => [
                    ['fr' => 'Mathématiques', 'ar' => 'رياضيات'],
                    ['fr' => 'Technologie', 'ar' => 'تكنولوجيا'],
                    ['fr' => 'Physique-Chimie', 'ar' => 'فيزياء-كيمياء'],
                    ['fr' => 'Mécanique', 'ar' => 'ميكانيك'],
                    ['fr' => 'Électricité', 'ar' => 'كهرباء'],
                    ['fr' => 'Français', 'ar' => 'فرنسية'],
                    ['fr' => 'Anglais', 'ar' => 'إنقليزية'],
                    ['fr' => 'Philosophie', 'ar' => 'فلسفة'],
                    ['fr' => 'Éducation Physique', 'ar' => 'تربية بدنية'],
                ]
            ],
        ];

        foreach ($niveaux as $code => $data) {
            $niveau = new Niveau();
            $niveau->setNomFr($data['fr']);
            $niveau->setNomAr($data['ar']);
            $niveau->setCode($code);
            $manager->persist($niveau);

            foreach ($data['matieres'] as $matiereData) {
                $matiere = new Matieres();
                $matiere->setNomFr($matiereData['fr']);
                $matiere->setNomAr($matiereData['ar']);
                $matiere->setIsActive(true);
                $matiere->setNiveau($niveau);
                $manager->persist($matiere);
            }
        }

        $manager->flush();
    }
}
