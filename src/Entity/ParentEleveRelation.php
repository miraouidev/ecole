<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ParentEleveRelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ParentEleveRelationRepository::class)]
#[ApiResource]
class ParentEleveRelation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'parentEleveRelations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['parent:read'])] // permet POST { parentEleveRelations: [...] }

    private ?Eleve $eleve = null;

    #[ORM\ManyToOne(inversedBy: 'parentEleveRelations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['eleve:read'])] // permet POST { parentEleveRelations: [...] }
    private ?ParentProfile $parent = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['eleve:read','parent:read',])] // permet POST { parentEleveRelations: [...] }
    private ?TypeRelation $typeRelation = null;

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

    public function getTypeRelation(): ?TypeRelation
    {
        return $this->typeRelation;
    }

    public function setTypeRelation(?TypeRelation $typeRelation): static
    {
        $this->typeRelation = $typeRelation;

        return $this;
    }
}
