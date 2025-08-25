<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\CodeNomTrait;
use App\Repository\TypeRelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TypeRelationRepository::class)]
#[ApiResource(
    routePrefix: '/annexe',
    normalizationContext: ['groups' => ['relation:read']],
    operations: [
        new GetCollection() // ✅ uniquement GET collection
    ]
)]
class TypeRelation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['relation:read'])]
    private ?int $id = null;

    use CodeNomTrait;

    public function getId(): ?int
    {
        return $this->id;
    }
}
