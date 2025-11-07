<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OnlyActiveProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $repo = $this->em->getRepository(\App\Entity\Groupe::class);
        return $repo->findBy(['isActive' => true]);
    }
}
