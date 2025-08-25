<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait CodeNomTrait
{
    #[ORM\Column(length: 100)]
    #[Groups(['relation:read','civilite:read','civilite:write','parent:read','parent:write','eleve:read','enseignant:read','enseignant:write'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['relation:read','civilite:read','civilite:write','parent:read','parent:write','eleve:read','enseignant:read','enseignant:write'])]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 10)]
    #[Groups(['relation:read','civilite:read','civilite:write','parent:read','parent:write','eleve:read','enseignant:read',])]
    private ?string $code = null;

    public function getNomFr(): ?string { return $this->nom_fr; }
    public function setNomFr(string $nom_fr): static { $this->nom_fr = $nom_fr; return $this; }

    public function getNomAr(): ?string { return $this->nom_ar; }
    public function setNomAr(string $nom_ar): static { $this->nom_ar = $nom_ar; return $this; }

    public function getCode(): ?string { return $this->code; }
    public function setCode(string $code): static { $this->code = strtoupper($code); return $this; }
}
