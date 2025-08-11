<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CodeNomTrait
{
    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 10)]
    private ?string $code = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }
}
