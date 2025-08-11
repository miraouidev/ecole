<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ParentEleveRelationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParentEleveRelationRepository::class)]
#[ApiResource]
class ParentEleveRelation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'parentEleveRelations')]
    private ?Eleve $eleve = null;

    #[ORM\ManyToOne(inversedBy: 'parentEleveRelations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParentProfile $parent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): static
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getParent(): ?ParentProfile
    {
        return $this->parent;
    }

    public function setParent(?ParentProfile $parent): static
    {
        $this->parent = $parent;

        return $this;
    }
}
