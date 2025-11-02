<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Repository\TypeNoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TypeNoteRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['typeNote:read']],
    denormalizationContext: ['groups' => ['typeNote:write']],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'nom_fr' => 'partial',
    'nom_ar' => 'partial',
])]
class TypeNote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['typeNote:read','mtn:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['typeNote:read', 'typeNote:write','mtn:read'])]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['typeNote:read', 'typeNote:write','mtn:read'])]
    private ?string $nom_fr = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['typeNote:read', 'typeNote:write','mtn:read'])]
    private ?bool $forAll = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAr(): ?string
    {
        return $this->nom_ar;
    }

    public function setNomAr(string $nom_ar): static
    {
        $this->nom_ar = $nom_ar;
        return $this;
    }

    public function getNomFr(): ?string
    {
        return $this->nom_fr;
    }

    public function setNomFr(?string $nom_fr): static
    {
        $this->nom_fr = $nom_fr;
        return $this;
    }

    public function isForAll(): ?bool
    {
        return $this->forAll;
    }

    public function setForAll(bool $forAll): static
    {
        $this->forAll = $forAll;

        return $this;
    }
}
