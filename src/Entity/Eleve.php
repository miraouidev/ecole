<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Repository\EleveRepository;
use App\State\EleveProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['eleve:read']],
    denormalizationContext: ['groups' => ['eleve:write']],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(processor: EleveProcessor::class),
        new Patch()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'nom_fr'     => 'partial',
    'nom_ar'     => 'partial',
    'prenom_fr'  => 'partial',
    'prenom_ar'  => 'partial',
    'groupe.id'  => 'exact',
    'parentEleveRelations.parent.id' => 'exact'
])]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['eleve:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['eleve:read', 'eleve:write','parent:read'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['eleve:read', 'eleve:write','parent:read'])]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 100)]
    #[Groups(['eleve:read', 'eleve:write','parent:read'])]
    private ?string $prenom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['eleve:read', 'eleve:write','parent:read'])]
    private ?string $prenom_ar = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['eleve:read', 'eleve:write','parent:read'])]
    private ?\DateTimeImmutable $dateNai = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['eleve:read', 'eleve:write','parent:read'])]
    private ?Groupe $groupe = null;

    /**
     * @var Collection<int, ParentEleveRelation>
     */
    #[ORM\OneToMany(targetEntity: ParentEleveRelation::class, mappedBy: 'eleve', cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['eleve:read'])]

    private Collection $parentEleveRelations;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    private ?GroupeMini $groupeMini = null;

    /**
     * @var Collection<int, NoteEleve>
     */
    #[ORM\OneToMany(targetEntity: NoteEleve::class, mappedBy: 'eleve', orphanRemoval: true)]
    private Collection $noteEleves;

    /**
     * @var Collection<int, Resultat>
     */
    #[ORM\OneToMany(targetEntity: Resultat::class, mappedBy: 'eleve')]
    private Collection $resultats;

    /**
     * @var Collection<int, Scolarite>
     */
    #[ORM\OneToMany(targetEntity: Scolarite::class, mappedBy: 'eleve')]
    #[Groups(['eleve:read', 'eleve:write','parent:read'])]
    private Collection $scolarites;

    public function __construct()
    {
        $this->parentEleveRelations = new ArrayCollection();
        $this->noteEleves = new ArrayCollection();
        $this->resultats = new ArrayCollection();
        $this->scolarites = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNomFr(): ?string { return $this->nom_fr; }
    public function setNomFr(string $nom_fr): static { $this->nom_fr = $nom_fr; return $this; }

    public function getNomAr(): ?string { return $this->nom_ar; }
    public function setNomAr(string $nom_ar): static { $this->nom_ar = $nom_ar; return $this; }

    public function getPrenomFr(): ?string { return $this->prenom_fr; }
    public function setPrenomFr(string $prenom_fr): static { $this->prenom_fr = $prenom_fr; return $this; }

    public function getPrenomAr(): ?string { return $this->prenom_ar; }
    public function setPrenomAr(string $prenom_ar): static { $this->prenom_ar = $prenom_ar; return $this; }

    public function getDateNai(): ?\DateTimeImmutable { return $this->dateNai; }
    public function setDateNai(?\DateTimeImmutable $dateNai): static { $this->dateNai = $dateNai; return $this; }

    public function getGroupe(): ?Groupe { return $this->groupe; }
    public function setGroupe(?Groupe $groupe): static { $this->groupe = $groupe; return $this; }

    /**
     * @return Collection<int, ParentEleveRelation>
     */
    public function getParentEleveRelations(): Collection { return $this->parentEleveRelations; }

    public function addParentEleveRelation(ParentEleveRelation $parentEleveRelation): static
    {
        if (!$this->parentEleveRelations->contains($parentEleveRelation)) {
            $this->parentEleveRelations->add($parentEleveRelation);
            $parentEleveRelation->setEleve($this);
        }
        return $this;
    }

    public function removeParentEleveRelation(ParentEleveRelation $parentEleveRelation): static
    {
        if ($this->parentEleveRelations->removeElement($parentEleveRelation)) {
            if ($parentEleveRelation->getEleve() === $this) {
                $parentEleveRelation->setEleve(null);
            }
        }
        return $this;
    }

    public function getGroupeMini(): ?GroupeMini
    {
        return $this->groupeMini;
    }

    public function setGroupeMini(?GroupeMini $groupeMini): static
    {
        $this->groupeMini = $groupeMini;

        return $this;
    }

    /**
     * @return Collection<int, NoteEleve>
     */
    public function getNoteEleves(): Collection
    {
        return $this->noteEleves;
    }

    public function addNoteElefe(NoteEleve $noteElefe): static
    {
        if (!$this->noteEleves->contains($noteElefe)) {
            $this->noteEleves->add($noteElefe);
            $noteElefe->setEleve($this);
        }

        return $this;
    }

    public function removeNoteElefe(NoteEleve $noteElefe): static
    {
        if ($this->noteEleves->removeElement($noteElefe)) {
            // set the owning side to null (unless already changed)
            if ($noteElefe->getEleve() === $this) {
                $noteElefe->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Resultat>
     */
    public function getResultats(): Collection
    {
        return $this->resultats;
    }

    public function addResultat(Resultat $resultat): static
    {
        if (!$this->resultats->contains($resultat)) {
            $this->resultats->add($resultat);
            $resultat->setEleve($this);
        }

        return $this;
    }

    public function removeResultat(Resultat $resultat): static
    {
        if ($this->resultats->removeElement($resultat)) {
            // set the owning side to null (unless already changed)
            if ($resultat->getEleve() === $this) {
                $resultat->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Scolarite>
     */
    public function getScolarites(): Collection
    {
        return $this->scolarites;
    }

    public function addScolarite(Scolarite $scolarite): static
    {
        if (!$this->scolarites->contains($scolarite)) {
            $this->scolarites->add($scolarite);
            $scolarite->setEleve($this);
        }

        return $this;
    }

    public function removeScolarite(Scolarite $scolarite): static
    {
        if ($this->scolarites->removeElement($scolarite)) {
            // set the owning side to null (unless already changed)
            if ($scolarite->getEleve() === $this) {
                $scolarite->setEleve(null);
            }
        }

        return $this;
    }
}
