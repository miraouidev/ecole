<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof User && $data->getPassword()) {
            $hashed = $this->passwordHasher->hashPassword($data, $data->getPassword());
            $data->setPassword($hashed);
        }

        // ensuite, déléguer au processor doctrine
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
