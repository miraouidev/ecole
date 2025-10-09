<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\RhStatusFamilleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RhStatusFamilleRepository::class)]
#[ORM\Table(name: 'rh_status_famille')]
#[ORM\UniqueConstraint(name: 'unique_code', columns: ['code'])]
#[UniqueEntity(fields: ['code'], message: 'Ce code de statut familial existe déjà.')]
#[ApiResource(
    routePrefix: '/rh',
    normalizationContext: ['groups' => ['rh_statusfamille:read']],
    denormalizationContext: ['groups' => ['rh_statusfamille:write']],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Delete()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'nom_fr' => 'partial',
    'nom_ar' => 'partial',
    'code'   => 'exact',
])]
class RhStatusFamille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rh_statusfamille:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rh_statusfamille:read', 'rh_statusfamille:write','rh_employe:read'])]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rh_statusfamille:read', 'rh_statusfamille:write','rh_employe:read'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 5, unique: true)]
    #[Groups(['rh_statusfamille:read', 'rh_statusfamille:write','rh_employe:read'])]
    private ?string $code = null;

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

    public function setNomFr(string $nom_fr): static
    {
        $this->nom_fr = $nom_fr;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }
}
