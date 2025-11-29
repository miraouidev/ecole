<?php
namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NiveauRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['niveau:read']],
    denormalizationContext: ['groups' => ['niveau:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'code' => 'exact',
    'nom_fr' => 'partial',
    'nom_ar' => 'partial',
])]
#[ApiFilter(BooleanFilter::class, properties: ['isActive'])]
class Niveau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['niveau:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['niveau:read','niveau:write'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['niveau:read','niveau:write'])]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 10, unique: true)]
    #[Groups(['niveau:read','niveau:write'])]
    private ?string $code = null;

    /**
     * @var Collection<int, NiveauMatiere>
     */
    #[ORM\OneToMany(targetEntity: NiveauMatiere::class, mappedBy: 'Niveau', orphanRemoval: true)]
    private Collection $niveauMatieres;

    /**
     * @var Collection<int, MatiereNiveau>
     */
    #[ORM\OneToMany(targetEntity: MatiereNiveau::class, mappedBy: 'niveau', orphanRemoval: true)]
    private Collection $matiereNiveaux;

    public function __construct()
    {
        $this->niveauMatieres = new ArrayCollection();
        $this->matiereNiveaux = new ArrayCollection();
    }

    use IsActiveTrait;

    public function getId(): ?int { return $this->id; }

    public function getNomFr(): ?string { return $this->nom_fr; }
    public function setNomFr(string $nom_fr): static { $this->nom_fr = $nom_fr; return $this; }

    public function getNomAr(): ?string { return $this->nom_ar; }
    public function setNomAr(string $nom_ar): static { $this->nom_ar = $nom_ar; return $this; }

    public function getCode(): ?string { return $this->code; }
    public function setCode(string $code): static { $this->code = strtoupper($code); return $this; }

    /**
     * @return Collection<int, NiveauMatiere>
     */
    public function getNiveauMatieres(): Collection
    {
        return $this->niveauMatieres;
    }

    public function addNiveauMatiere(NiveauMatiere $niveauMatiere): static
    {
        if (!$this->niveauMatieres->contains($niveauMatiere)) {
            $this->niveauMatieres->add($niveauMatiere);
            $niveauMatiere->setNiveau($this);
        }

        return $this;
    }

    public function removeNiveauMatiere(NiveauMatiere $niveauMatiere): static
    {
        if ($this->niveauMatieres->removeElement($niveauMatiere)) {
            // set the owning side to null (unless already changed)
            if ($niveauMatiere->getNiveau() === $this) {
                $niveauMatiere->setNiveau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MatiereNiveau>
     */
    public function getMatiereNiveaux(): Collection
    {
        return $this->matiereNiveaux;
    }

    public function addMatiereNiveau(MatiereNiveau $matiereNiveau): static
    {
        if (!$this->matiereNiveaux->contains($matiereNiveau)) {
            $this->matiereNiveaux->add($matiereNiveau);
            $matiereNiveau->setNiveau($this);
        }

        return $this;
    }

    public function removeMatiereNiveau(MatiereNiveau $matiereNiveau): static
    {
        if ($this->matiereNiveaux->removeElement($matiereNiveau)) {
            // set the owning side to null (unless already changed)
            if ($matiereNiveau->getNiveau() === $this) {
                $matiereNiveau->setNiveau(null);
            }
        }

        return $this;
    }
}
