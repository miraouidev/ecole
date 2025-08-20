<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait PersonTrait
{
    #[ORM\Column(length: 100)]
    #[Groups(['admin:read', 'admin:write','admin:patch'])]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Groups(['admin:read', 'admin:write','admin:patch'])]
    private ?string $prenom = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch'])]
    private ?\DateTimeImmutable $dateNai = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch'])]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch'])]
    private ?string $cin = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch'])]
    private ?string $phone = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch'])]
    private ?string $mobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch'])]
    private ?string $adresse = null;

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): static { $this->prenom = $prenom; return $this; }

    public function getDateNai(): ?\DateTimeImmutable { return $this->dateNai; }
    public function setDateNai(?\DateTimeImmutable $dateNai): static { $this->dateNai = $dateNai; return $this; }

    public function getStartAt(): ?\DateTimeImmutable { return $this->startAt; }
    public function setStartAt(?\DateTimeImmutable $startAt): static { $this->startAt = $startAt; return $this; }

    public function getCin(): ?string { return $this->cin; }
    public function setCin(?string $cin): static { $this->cin = $cin; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): static { $this->phone = $phone; return $this; }

    public function getMobile(): ?string { return $this->mobile; }
    public function setMobile(?string $mobile): static { $this->mobile = $mobile; return $this; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): static { $this->adresse = $adresse; return $this; }
}
