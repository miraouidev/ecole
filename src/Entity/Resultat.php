<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ResultatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultatRepository::class)]
#[ApiResource]
class Resultat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isAdmis = null;

    #[ORM\Column(nullable: true)]
    private ?float $moyenneSemestre1 = null;

    #[ORM\Column(nullable: true)]
    private ?float $moyenneSemestre2 = null;

    #[ORM\Column(nullable: true)]
    private ?float $moyenneSemestre3 = null;

    #[ORM\ManyToOne(inversedBy: 'resultats')]
    private ?Eleve $eleve = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAdmis(): ?bool
    {
        return $this->isAdmis;
    }

    public function setIsAdmis(?bool $isAdmis): static
    {
        $this->isAdmis = $isAdmis;

        return $this;
    }

    public function getMoyenneSemestre1(): ?float
    {
        return $this->moyenneSemestre1;
    }

    public function setMoyenneSemestre1(?float $moyenneSemestre1): static
    {
        $this->moyenneSemestre1 = $moyenneSemestre1;

        return $this;
    }

    public function getMoyenneSemestre2(): ?float
    {
        return $this->moyenneSemestre2;
    }

    public function setMoyenneSemestre2(?float $moyenneSemestre2): static
    {
        $this->moyenneSemestre2 = $moyenneSemestre2;

        return $this;
    }

    public function getMoyenneSemestre3(): ?float
    {
        return $this->moyenneSemestre3;
    }

    public function setMoyenneSemestre3(?float $moyenneSemestre3): static
    {
        $this->moyenneSemestre3 = $moyenneSemestre3;

        return $this;
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
}
