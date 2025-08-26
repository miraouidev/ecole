<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\RoleAppRepository;

#[ORM\Entity(repositoryClass: RoleAppRepository::class)]
#[ApiResource(
    routePrefix: '/admin',
    normalizationContext: ['groups' => ['role:read']],
    denormalizationContext: ['groups' => ['role:write']],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN')"),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'nom' => 'partial',
    'code' => 'exact'
])]
class RoleApp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['role:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['role:read', 'role:write'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['role:read', 'role:write'])]
    private ?string $info = null;

    #[ORM\Column(length: 30, unique: true)]
    #[Groups(['role:read', 'role:write'])]
    private ?string $code = null;

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getInfo(): ?string { return $this->info; }
    public function setInfo(?string $info): static { $this->info = $info; return $this; }
    public function getCode(): ?string { return $this->code; }
    public function setCode(string $code): static { $this->code = $code; return $this; }
}
