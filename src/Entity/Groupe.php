<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ApiResource]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Niveau $niveau = null;

    #[ORM\ManyToOne(inversedBy: 'groupes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnneeScolaireCourante $anneeScolaire = null;

    /**
     * @var Collection<int, Eleve>
     */
    #[ORM\OneToMany(targetEntity: Eleve::class, mappedBy: 'groupe')]
    private Collection $eleves;

    /**
     * @var Collection<int, MatiereClasseProf>
     */
    #[ORM\OneToMany(targetEntity: MatiereClasseProf::class, mappedBy: 'groupe')]
    private Collection $matiereClasseProfs;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $this->matiereClasseProfs = new ArrayCollection();
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

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): static
    {
        $this->niveau = $niveau;

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
     * @return Collection<int, Eleve>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleve $elefe): static
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves->add($elefe);
            $elefe->setGroupe($this);
        }

        return $this;
    }

    public function removeElefe(Eleve $elefe): static
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getGroupe() === $this) {
                $elefe->setGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MatiereClasseProf>
     */
    public function getMatiereClasseProfs(): Collection
    {
        return $this->matiereClasseProfs;
    }

    public function addMatiereClasseProf(MatiereClasseProf $matiereClasseProf): static
    {
        if (!$this->matiereClasseProfs->contains($matiereClasseProf)) {
            $this->matiereClasseProfs->add($matiereClasseProf);
            $matiereClasseProf->setGroupe($this);
        }

        return $this;
    }

    public function removeMatiereClasseProf(MatiereClasseProf $matiereClasseProf): static
    {
        if ($this->matiereClasseProfs->removeElement($matiereClasseProf)) {
            // set the owning side to null (unless already changed)
            if ($matiereClasseProf->getGroupe() === $this) {
                $matiereClasseProf->setGroupe(null);
            }
        }

        return $this;
    }
}
