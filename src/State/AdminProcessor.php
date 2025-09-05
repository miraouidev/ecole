<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Admininstrateur;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AdminProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        private RequestStack $requestStack,
        private UserRepository $userRepo,    // 👈 inject this
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Only handle Admin creation/update payloads
        if (!$data instanceof Admininstrateur) {
            return $data;
        }

        $request = $this->requestStack->getCurrentRequest();
        $payload = $request?->toArray() ?? [];

        // 1) Enforce presence of 'userinfo'
        if (!isset($payload['userinfo']) || !is_array($payload['userinfo'])) {
            // 400 Bad Request
            throw new BadRequestHttpException("Le champ 'userinfo' est requis pour créer l'utilisateur lié.");
        }

        $userinfo = $payload['userinfo'];

        // 2) Minimal validation of required userinfo fields
        $username = isset($userinfo['username']) && is_string($userinfo['username']) ? trim($userinfo['username']) : null;
        $plainPassword = isset($userinfo['password']) && is_string($userinfo['password']) ? (string) $userinfo['password'] : null;

        if (!$username || !$plainPassword) {
            // 422 Unprocessable Entity for semantic validation issues
            throw new UnprocessableEntityHttpException("Les champs 'userinfo.username' et 'userinfo.password' sont requis.");
        }

        // Optional fields with sane defaults
        $email = isset($userinfo['email']) && is_string($userinfo['email']) ? trim($userinfo['email']) : null;
        $roles = [];
        if (isset($userinfo['roles']) && is_array($userinfo['roles'])) {
            // Keep only strings, uppercase and de-dup, always ensure at least ROLE_USER
            $roles = array_values(array_unique(array_map(
                static fn($r) => strtoupper((string) $r),
                array_filter($userinfo['roles'], 'is_string')
            )));
        }
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }

        $type = isset($userinfo['type']) && is_string($userinfo['type']) ? $userinfo['type'] : 'personel';
        $isActive = isset($userinfo['isActive']) ? (bool) $userinfo['isActive'] : true;

        // 🚫 Pre-validate uniqueness (fast path)
        if ($this->userRepo->findOneBy(['username' => $username])) {
            throw new UnprocessableEntityHttpException("Le nom d'utilisateur existe déjà.");
        }
        if ($email && $this->userRepo->findOneBy(['email' => $email])) {
            throw new UnprocessableEntityHttpException("L'email existe déjà.");
        }

        // 3) Build and hash the User
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setType($type);
        $user->setRoles($roles);
        if (method_exists($user, 'setIsActive')) {
            $user->setIsActive($isActive);
        }

        $hashed = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashed);

        // 4) Link entities
        // If your Administrateur entity has setUser(User $u) and cascade={"persist"} on the relation,
        // persisting $data is enough. Otherwise persist both explicitly.
        $data->setUser($user);

        // 5) Persist
        // If no cascade persist on Administrateur->user, uncomment the next line:
        // $this->em->persist($user);
        $this->em->persist($data);
        $this->em->flush();

        // Optional: dispatch an event / message to email the initial credentials, etc.

        return $data;
    }
}
