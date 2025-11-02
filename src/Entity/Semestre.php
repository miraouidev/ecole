<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\SemestreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SemestreRepository::class)]
#[ApiResource(
    operations: [
        // GET all semestres filtered by année scolaire
        new GetCollection(
            uriTemplate: '/semestres',
            filters: [SearchFilter::class],
            normalizationContext: ['groups' => ['semestre:read']],
            paginationEnabled: false
        ),
        // PATCH single semestre (admin only)
        new Patch(
            uriTemplate: '/semestres/{id}',
            denormalizationContext: ['groups' => ['semestre:write']],
            normalizationContext: ['groups' => ['semestre:read']],
            security: "is_granted('ROLE_ADMIN')"
        ),
    ],
    routePrefix: '/scolarite',
)]
#[ApiFilter(SearchFilter::class, properties: [
    'anneeScolaire.id' => 'exact'
])]
class Semestre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['semestre:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['semestre:read', 'semestre:write'])]
    private ?int $number = null;

    #[ORM\Column(length: 255)]
    #[Groups(['semestre:read', 'semestre:write'])]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 255)]
    #[Groups(['semestre:read', 'semestre:write'])]
    private ?string $nom_fr = null;

    #[ORM\ManyToOne(inversedBy: 'semestres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnneeScolaireCourante $anneeScolaire = null;

    /**
     * @var Collection<int, MatieresTypeNote>
     */
    #[ORM\OneToMany(targetEntity: MatieresTypeNote::class, mappedBy: 'semestre')]
    private Collection $matieresTypeNotes;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['semestre:read', 'semestre:write'])]
    private ?bool $isRemplie = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['semestre:read', 'semestre:write'])]
    private ?bool $isAffiche = null;

    public function __construct()
    {
        $this->matieresTypeNotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;
        return $this;
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

    public function getAnneeScolaire(): ?AnneeScolaireCourante
    {
        return $this->anneeScolaire;
    }

    public function setAnneeScolaire(?AnneeScolaireCourante $anneeScolaire): static
    {
        $this->anneeScolaire = $anneeScolaire;
        return $this;
    }

    /**
     * @return Collection<int, MatieresTypeNote>
     */
    public function getMatieresTypeNotes(): Collection
    {
        return $this->matieresTypeNotes;
    }

    public function addMatieresTypeNote(MatieresTypeNote $matieresTypeNote): static
    {
        if (!$this->matieresTypeNotes->contains($matieresTypeNote)) {
            $this->matieresTypeNotes->add($matieresTypeNote);
            $matieresTypeNote->setSemestre($this);
        }
        return $this;
    }

    public function removeMatieresTypeNote(MatieresTypeNote $matieresTypeNote): static
    {
        if ($this->matieresTypeNotes->removeElement($matieresTypeNote)) {
            if ($matieresTypeNote->getSemestre() === $this) {
                $matieresTypeNote->setSemestre(null);
            }
        }
        return $this;
    }

    public function isRemplie(): ?bool
    {
        return $this->isRemplie;
    }

    public function setIsRemplie(bool $isRemplie): static
    {
        $this->isRemplie = $isRemplie;
        return $this;
    }

    public function isAffiche(): ?bool
    {
        return $this->isAffiche;
    }

    public function setIsAffiche(bool $isAffiche): static
    {
        $this->isAffiche = $isAffiche;
        return $this;
    }
}
