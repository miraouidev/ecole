<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\HasLangueTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\SiteOurProgramTicketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteOurProgramTicketRepository::class)]
#[ApiResource]
class SiteOurProgramTicket
{

    ///  for section 5.1

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombre = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreHeure = null;

    #[ORM\Column(nullable: true)]
    private ?int $lesson = null;

     use IsActiveTrait;
         use HasLangueTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(?int $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNombreHeure(): ?int
    {
        return $this->nombreHeure;
    }

    public function setNombreHeure(?int $nombreHeure): static
    {
        $this->nombreHeure = $nombreHeure;

        return $this;
    }

    public function getLesson(): ?int
    {
        return $this->lesson;
    }

    public function setLesson(?int $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }
}
