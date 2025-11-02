<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GroupeMatierTypeNoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeMatierTypeNoteRepository::class)]
#[ApiResource]
class GroupeMatierTypeNote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupe $groupe = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MatieresTypeNote $matieresTypeNote = null;

    #[ORM\Column]
    private ?bool $adminValide = null;

    #[ORM\Column]
    private ?bool $profValide = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateAffiche = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMatieresTypeNote(): ?MatieresTypeNote
    {
        return $this->matieresTypeNote;
    }

    public function setMatieresTypeNote(?MatieresTypeNote $matieresTypeNote): static
    {
        $this->matieresTypeNote = $matieresTypeNote;

        return $this;
    }

    public function isAdminValide(): ?bool
    {
        return $this->adminValide;
    }

    public function setAdminValide(bool $adminValide): static
    {
        $this->adminValide = $adminValide;

        return $this;
    }

    public function isProfValide(): ?bool
    {
        return $this->profValide;
    }

    public function setProfValide(bool $profValide): static
    {
        $this->profValide = $profValide;

        return $this;
    }

    public function getDateAffiche(): ?\DateTimeImmutable
    {
        return $this->dateAffiche;
    }

    public function setDateAffiche(?\DateTimeImmutable $dateAffiche): static
    {
        $this->dateAffiche = $dateAffiche;

        return $this;
    }
}
