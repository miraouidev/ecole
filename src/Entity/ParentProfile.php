<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\PersonTrait;
use App\Repository\ParentProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParentProfileRepository::class)]
#[ApiResource]
class ParentProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'parent', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    /**
     * @var Collection<int, ParentEleveRelation>
     */
    #[ORM\OneToMany(targetEntity: ParentEleveRelation::class, mappedBy: 'parent')]
    private Collection $parentEleveRelations;

    #[ORM\ManyToOne]
    private ?Civilite $civilite = null;

    public function __construct()
    {
        $this->parentEleveRelations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    use PersonTrait;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setParent(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getParent() !== $this) {
            $user->setParent($this);
        }

        $this->user = $user;

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
            $parentEleveRelation->setParent($this);
        }

        return $this;
    }

    public function removeParentEleveRelation(ParentEleveRelation $parentEleveRelation): static
    {
        if ($this->parentEleveRelations->removeElement($parentEleveRelation)) {
            // set the owning side to null (unless already changed)
            if ($parentEleveRelation->getParent() === $this) {
                $parentEleveRelation->setParent(null);
            }
        }

        return $this;
    }

    public function getCivilite(): ?Civilite
    {
        return $this->civilite;
    }

    public function setCivilite(?Civilite $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }
}
