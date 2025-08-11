<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\HasLangueTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\SiteTestimonialsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteTestimonialsRepository::class)]
#[ApiResource]
class SiteTestimonials
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clientName = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreEtoile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $text = null;

    use IsActiveTrait;
        use HasLangueTrait;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(?string $clientName): static
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getNombreEtoile(): ?int
    {
        return $this->nombreEtoile;
    }

    public function setNombreEtoile(?int $nombreEtoile): static
    {
        $this->nombreEtoile = $nombreEtoile;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }
}
