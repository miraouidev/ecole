<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NoteEleveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteEleveRepository::class)]
#[ApiResource]
class NoteEleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $valeur = null;

    #[ORM\Column(nullable: true)]
    private ?float $coefficient = null;

    #[ORM\ManyToOne(inversedBy: 'noteEleves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?eleve $eleve = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MatieresTypeNote $typeNote = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(?float $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(?float $coefficient): static
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getEleve(): ?eleve
    {
        return $this->eleve;
    }

    public function setEleve(?eleve $eleve): static
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getTypeNote(): ?MatieresTypeNote
    {
        return $this->typeNote;
    }

    public function setTypeNote(?MatieresTypeNote $typeNote): static
    {
        $this->typeNote = $typeNote;

        return $this;
    }
}
