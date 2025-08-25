<?php

namespace App\DataFixtures;

use App\Entity\RoleApp;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleAppFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $roles = [
            [
                'code' => 'ROLE_ADMIN',
                'nom' => 'Administrateur',
                'info' => 'Gestion des utilisateurs'
            ],
            [
                'code' => 'ROLE_MANAGER',
                'nom' => 'Manager',
                'info' => 'Gestion des annonces et informations'
            ],
            [
                'code' => 'ROLE_SITE',
                'nom' => 'Site',
                'info' => 'Gestion du site et contenu'
            ],
            [
                'code' => 'ROLE_SCOLAIRE',
                'nom' => 'Scolaire',
                'info' => 'Gestion professeurs, matières, groupes, notes et moyennes'
            ],
            [
                'code' => 'ROLE_ELEVE',
                'nom' => 'Élève',
                'info' => 'Accès élève et parent'
            ],
            [
                'code' => 'ROLE_COMPTA',
                'nom' => 'Comptabilité',
                'info' => 'Gestion comptabilité, reçus et paiements'
            ],
        ];

        foreach ($roles as $data) {
            $role = new RoleApp();
            $role->setCode($data['code']);
            $role->setNom($data['nom']);
            $role->setInfo($data['info']);
            $manager->persist($role);
        }

        $manager->flush();
    }
}
