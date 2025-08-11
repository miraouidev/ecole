<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\PersonTrait;
use App\Repository\AdmininstrateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdmininstrateurRepository::class)]
#[ApiResource]
class Admininstrateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'admininstrateur', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Civilite $civilite = null;

    #[ORM\Column(length: 100)]
    
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
            $this->user->setAdmininstrateur(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getAdmininstrateur() !== $this) {
            $user->setAdmininstrateur($this);
        }

        $this->user = $user;

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
