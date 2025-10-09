<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\RhJourFerieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RhJourFerieRepository::class)]
#[ORM\Table(name: 'rh_jour_ferie')]
#[ORM\UniqueConstraint(name: 'unique_date', columns: ['date'])]
#[UniqueEntity(fields: ['date'], message: 'Un jour férié existe déjà pour cette date.')]
#[ApiResource(
    routePrefix: '/rh',
    normalizationContext: ['groups' => ['rh_jourferie:read']],
    denormalizationContext: ['groups' => ['rh_jourferie:write']],
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
    'libelle_fr' => 'partial',
    'libelle_ar' => 'partial',
])]
#[ApiFilter(DateFilter::class, properties: ['date'])]
#[ApiFilter(BooleanFilter::class, properties: ['paye'])]
class RhJourFerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rh_jourferie:read'])]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    #[Groups(['rh_jourferie:read', 'rh_jourferie:write'])]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rh_jourferie:read', 'rh_jourferie:write'])]
    private ?string $libelle_ar = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rh_jourferie:read', 'rh_jourferie:write'])]
    private ?string $libelle_fr = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['rh_jourferie:read', 'rh_jourferie:write'])]
    private ?bool $paye = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getLibelleAr(): ?string
    {
        return $this->libelle_ar;
    }

    public function setLibelleAr(string $libelle_ar): static
    {
        $this->libelle_ar = $libelle_ar;
        return $this;
    }

    public function getLibelleFr(): ?string
    {
        return $this->libelle_fr;
    }

    public function setLibelleFr(string $libelle_fr): static
    {
        $this->libelle_fr = $libelle_fr;
        return $this;
    }

    public function isPaye(): ?bool
    {
        return $this->paye;
    }

    public function getIsPaye(): ?bool
    {
        return $this->paye;
    }

    public function setPaye(bool $paye): static
    {
        $this->paye = $paye;
        return $this;
    }
}
