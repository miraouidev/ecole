<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Entity\Traits\CodeNomTrait;
use App\Repository\CiviliteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CiviliteRepository::class)]
#[ApiResource(
    routePrefix: '/annexe',
    normalizationContext: ['groups' => ['civilite:read']],
    denormalizationContext: ['groups' => ['civilite:write']],
    operations: [
        new Get(),
        new GetCollection(),
    ],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true
)]
class Civilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['civilite:read', 'parent:read','enseignant:read','enseignant:write','admin:read'])]
    private ?int $id = null;

    use CodeNomTrait;

    public function getId(): ?int
    {
        return $this->id;
    }
}
