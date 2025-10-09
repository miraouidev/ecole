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
use App\Repository\RhTypeCongeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RhTypeCongeRepository::class)]
#[ORM\Table(name: 'rh_type_conge')]
#[ORM\UniqueConstraint(name: 'unique_libelle_fr', columns: ['libelle_fr'])]
#[UniqueEntity(fields: ['libelle_fr'], message: 'Ce type de congé existe déjà.')]
#[ApiResource(
    routePrefix: '/rh',
    normalizationContext: ['groups' => ['rh_typeconge:read']],
    denormalizationContext: ['groups' => ['rh_typeconge:write']],
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
    'libelle_fr' => 'partial',
    'libelle_ar' => 'partial',
])]
class RhTypeConge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rh_typeconge:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rh_typeconge:read', 'rh_typeconge:write','rh_conge:read'])]
    private ?string $libelle_ar = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['rh_typeconge:read', 'rh_typeconge:write','rh_conge:read'])]
    private ?string $libelle_fr = null;

    #[ORM\Column(length: 10)]
    #[Groups(['rh_typeconge:read', 'rh_typeconge:write','rh_conge:read'])]
    private ?string $coleur = null;

    /**
     * @var Collection<int, RhConge>
     */
    #[ORM\OneToMany(targetEntity: RhConge::class, mappedBy: 'typeConge')]
    #[Groups(['rh_typeconge:read'])]
    private Collection $rhConges;

    public function __construct()
    {
        $this->rhConges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setLibelleFr(?string $libelle_fr): static
    {
        $this->libelle_fr = $libelle_fr;
        return $this;
    }

    public function getColeur(): ?string
    {
        return $this->coleur;
    }

    public function setColeur(string $coleur): static
    {
        $this->coleur = $coleur;
        return $this;
    }

    /**
     * @return Collection<int, RhConge>
     */
    public function getRhConges(): Collection
    {
        return $this->rhConges;
    }

    public function addRhConge(RhConge $rhConge): static
    {
        if (!$this->rhConges->contains($rhConge)) {
            $this->rhConges->add($rhConge);
            $rhConge->setTypeConge($this);
        }
        return $this;
    }

    public function removeRhConge(RhConge $rhConge): static
    {
        if ($this->rhConges->removeElement($rhConge)) {
            if ($rhConge->getTypeConge() === $this) {
                $rhConge->setTypeConge(null);
            }
        }
        return $this;
    }
}
