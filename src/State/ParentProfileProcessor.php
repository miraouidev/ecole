<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ParentProfile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParentProfileProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        private RequestStack $requestStack
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof ParentProfile) {
            return $data;
        }

        $request = $this->requestStack->getCurrentRequest();
        $payload = $request?->toArray();        // Générer un mot de passe aléatoire
        $plainPassword = bin2hex(random_bytes(4)); // ex: "a3f9b8c2"

        $plainPassword = 123456; // ex: "a3f9b8c2"
        // Créer un nouvel utilisateur lié au parent
        $user = new User();
        $user->setUsername($data->getCin()); // CIN comme username
        $user->setEmail(null); /// set email
        $user->setType(User::TYPE_PARENT);
        $user->setRoles(['ROLE_PARENT']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $plainPassword)
        );

        // Lier les deux entités
        $data->setUser($user);
        // ✅ Si un email est passé dans la requête JSON, on l’ajoute
        if (isset($payload['user']['email'])) {
            $user->setEmail($payload['user']['email']);
        }
        // Persister automatiquement via cascade={"persist"}
        $this->em->persist($data);
        $this->em->flush();

        // ⚠️ tu peux logger / envoyer le plainPassword par email ou API
        // pour que le parent puisse se connecter

        return $data;
    }
}
