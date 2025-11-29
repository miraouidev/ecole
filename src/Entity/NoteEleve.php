<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NoteEleveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteEleveRepository::class)]
#[ApiResource(
    operations: [
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Post(),
        new \ApiPlatform\Metadata\Patch(),
        new \ApiPlatform\Metadata\Delete(),
        new \ApiPlatform\Metadata\Post(
            uriTemplate: '/notes/bulk',
            controller: \App\Controller\GradeEntryController::class,
            name: 'grade_entry_bulk'
        )
    ]
)]
class NoteEleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $valeur = null;

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
