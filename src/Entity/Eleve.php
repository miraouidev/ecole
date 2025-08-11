<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
#[ApiResource]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateNai = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupe $groupe = null;

    /**
     * @var Collection<int, ParentEleveRelation>
     */
    #[ORM\OneToMany(targetEntity: ParentEleveRelation::class, mappedBy: 'eleve')]
    private Collection $parentEleveRelations;

    public function __construct()
    {
        $this->parentEleveRelations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNai(): ?\DateTimeImmutable
    {
        return $this->dateNai;
    }

    public function setDateNai(?\DateTimeImmutable $dateNai): static
    {
        $this->dateNai = $dateNai;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, ParentEleveRelation>
     */
    public function getParentEleveRelations(): Collection
    {
        return $this->parentEleveRelations;
    }

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
            // set the owning side to null (unless already changed)
            if ($parentEleveRelation->getEleve() === $this) {
                $parentEleveRelation->setEleve(null);
            }
        }

        return $this;
    }
}
