<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\EcoleInfoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcoleInfoRepository::class)]
#[ApiResource(
    routePrefix: '/admin',
    operations: [
        new GetCollection(),
        new Patch()
    ]
)]
class EcoleInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 255)]
    private ?string $npm_fr = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_fr = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_ar = null;

    #[ORM\Column(length: 255)]
    private ?string $responsable_ar = null;

    #[ORM\Column(length: 255)]
    private ?string $responsable_fr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAr(): ?string
    {
        return $this->nom_ar;
    }

    public function setNomAr(string $nom_ar): static
    {
        $this->nom_ar = $nom_ar;
        return $this;
    }

    public function getNpmFr(): ?string
    {
        return $this->npm_fr;
    }

    public function setNpmFr(string $npm_fr): static
    {
        $this->npm_fr = $npm_fr;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getAdresseFr(): ?string
    {
        return $this->adresse_fr;
    }

    public function setAdresseFr(string $adresse_fr): static
    {
        $this->adresse_fr = $adresse_fr;
        return $this;
    }

    public function getAdresseAr(): ?string
    {
        return $this->adresse_ar;
    }

    public function setAdresseAr(string $adresse_ar): static
    {
        $this->adresse_ar = $adresse_ar;
        return $this;
    }

    public function getResponsableAr(): ?string
    {
        return $this->responsable_ar;
    }

    public function setResponsableAr(string $responsable_ar): static
    {
        $this->responsable_ar = $responsable_ar;
        return $this;
    }

    public function getResponsableFr(): ?string
    {
        return $this->responsable_fr;
    }

    public function setResponsableFr(string $responsable_fr): static
    {
        $this->responsable_fr = $responsable_fr;
        return $this;
    }
}
