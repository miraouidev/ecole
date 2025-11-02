<?php
namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Repository\MatieresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MatieresRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['matiere:read']],
    denormalizationContext: ['groups' => ['matiere:write']],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true,
    operations: [new Get(), new GetCollection(), new Post(), new Patch()]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'nom_fr' => 'partial',
    'nom_ar' => 'partial',
    'niveau.code' => 'partial',
])]
class Matieres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['matiere:read','enseignant:read','enseignant:write','mtn:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['matiere:read', 'matiere:write','enseignant:read','enseignant:write','mtn:read'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['matiere:read', 'matiere:write','enseignant:read','enseignant:write','mtn:read'])]
    private ?string $nom_ar = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['matiere:read', 'matiere:write'])]
    private ?bool $isActive = true;

    /**
     * @var Collection<int, Enseignant>
     */
    #[ORM\OneToMany(targetEntity: Enseignant::class, mappedBy: 'matiere')]
    private Collection $enseignants;

    /**
     * @var Collection<int, MatieresTypeNote>
     */
    #[ORM\OneToMany(targetEntity: MatieresTypeNote::class, mappedBy: 'matiere', orphanRemoval: true)]
    private Collection $matieresTypeNotes;

    public function __construct()
    {
        $this->enseignants = new ArrayCollection();
        $this->matieresTypeNotes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNomFr(): ?string { return $this->nom_fr; }
    public function setNomFr(string $nom_fr): static { $this->nom_fr = $nom_fr; return $this; }
    public function getNomAr(): ?string { return $this->nom_ar; }
    public function setNomAr(string $nom_ar): static { $this->nom_ar = $nom_ar; return $this; }
    public function isActive(): ?bool { return $this->isActive; }
    public function getIsActive(): ?bool { return $this->isActive; }
    public function setIsActive(bool $isActive): static { $this->isActive = $isActive; return $this; }

    /**
     * @return Collection<int, Enseignant>
     */
    public function getEnseignants(): Collection
    {
        return $this->enseignants;
    }

    public function addEnseignant(Enseignant $enseignant): static
    {
        if (!$this->enseignants->contains($enseignant)) {
            $this->enseignants->add($enseignant);
            $enseignant->setMatiere($this);
        }

        return $this;
    }

    public function removeEnseignant(Enseignant $enseignant): static
    {
        if ($this->enseignants->removeElement($enseignant)) {
            // set the owning side to null (unless already changed)
            if ($enseignant->getMatiere() === $this) {
                $enseignant->setMatiere(null);
            }
        }

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
            $matieresTypeNote->setMatiere($this);
        }

        return $this;
    }

    public function removeMatieresTypeNote(MatieresTypeNote $matieresTypeNote): static
    {
        if ($this->matieresTypeNotes->removeElement($matieresTypeNote)) {
            // set the owning side to null (unless already changed)
            if ($matieresTypeNote->getMatiere() === $this) {
                $matieresTypeNote->setMatiere(null);
            }
        }

        return $this;
    }
}
