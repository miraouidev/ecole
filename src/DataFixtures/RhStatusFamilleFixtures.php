<?php

namespace App\DataFixtures;

use App\Entity\RhStatusFamille;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RhStatusFamilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statuts = [
            ['nom_fr' => 'Célibataire', 'nom_ar' => 'أعزب', 'code' => 'CEL'],
            ['nom_fr' => 'Marié(e)', 'nom_ar' => 'متزوج', 'code' => 'MAR'],
            ['nom_fr' => 'Divorcé(e)', 'nom_ar' => 'مطلق', 'code' => 'DIV'],
            ['nom_fr' => 'Veuf(ve)', 'nom_ar' => 'أرمل', 'code' => 'VEU'],
            ['nom_fr' => 'Séparé(e)', 'nom_ar' => 'منفصل', 'code' => 'SEP'],
            ['nom_fr' => 'Autre', 'nom_ar' => 'آخر', 'code' => 'AUT']
        ];

        foreach ($statuts as $statut) {
            $entity = new RhStatusFamille();
            $entity->setNomFr($statut['nom_fr']);
            $entity->setNomAr($statut['nom_ar']);
            $entity->setCode($statut['code']);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
