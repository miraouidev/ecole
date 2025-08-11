<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MatiereClasseProfRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereClasseProfRepository::class)]
#[ApiResource]
class MatiereClasseProf
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'matiereClasseProfs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupe $groupe = null;

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
}
