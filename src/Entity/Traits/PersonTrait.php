<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait PersonTrait
{
    #[ORM\Column(length: 100)]
    #[Groups(['admin:read', 'admin:write','admin:patch','parent:read','parent:write','eleve:read','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['admin:read', 'admin:write','admin:patch','parent:read','parent:write','eleve:read','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 100)]
    #[Groups(['admin:read', 'admin:write','admin:patch','parent:read','parent:write','eleve:read','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?string $prenom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['admin:read', 'admin:write','admin:patch','parent:read','parent:write','eleve:read','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?string $prenom_ar = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch','parent:read','parent:write','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?\DateTimeImmutable $dateNai = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch','parent:read','parent:write','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?string $cin = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch','parent:read','parent:write','eleve:read','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?string $phone = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch','parent:read','parent:write','eleve:read','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?string $mobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['admin:read', 'admin:write','admin:patch','parent:read','parent:write','enseignant:read','enseignant:write','enseignant:patch'])]
    private ?string $adresse = null;

    // --- Getters & Setters ---
    public function getNomFr(): ?string { return $this->nom_fr; }
    public function setNomFr(string $nom_fr): static { $this->nom_fr = $nom_fr; return $this; }

    public function getNomAr(): ?string { return $this->nom_ar; }
    public function setNomAr(string $nom_ar): static { $this->nom_ar = $nom_ar; return $this; }

    public function getPrenomFr(): ?string { return $this->prenom_fr; }
    public function setPrenomFr(string $prenom_fr): static { $this->prenom_fr = $prenom_fr; return $this; }

    public function getPrenomAr(): ?string { return $this->prenom_ar; }
    public function setPrenomAr(string $prenom_ar): static { $this->prenom_ar = $prenom_ar; return $this; }

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
