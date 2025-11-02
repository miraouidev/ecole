<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // 🔐 Admin user
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@ecole.com');
        $admin->setType(User::TYPE_PERSONEL);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin123')
        );
        $manager->persist($admin);

        // 👨‍👧 Parent user (sans email)
        $parent = new User();
        $parent->setUsername('parent01');
        $parent->setEmail(null);
        $parent->setType(User::TYPE_PARENT);
        $parent->setRoles(['ROLE_PARENT']);
        $parent->setPassword(
            $this->passwordHasher->hashPassword($parent, 'parent123')
        );
        $manager->persist($parent);

        $manager->flush();
    }
}
