<?php
namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\NiveauRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NiveauRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['niveau:read']],
    denormalizationContext: ['groups' => ['niveau:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'code' => 'exact',
    'nom_fr' => 'partial',
    'nom_ar' => 'partial',
])]
#[ApiFilter(BooleanFilter::class, properties: ['isActive'])]
class Niveau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['niveau:read', 'matiere:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['niveau:read','niveau:write','matiere:read'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['niveau:read','niveau:write','matiere:read'])]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 10, unique: true)]
    #[Groups(['niveau:read','niveau:write','matiere:read'])]
    private ?string $code = null;

    use IsActiveTrait;

    public function getId(): ?int { return $this->id; }

    public function getNomFr(): ?string { return $this->nom_fr; }
    public function setNomFr(string $nom_fr): static { $this->nom_fr = $nom_fr; return $this; }

    public function getNomAr(): ?string { return $this->nom_ar; }
    public function setNomAr(string $nom_ar): static { $this->nom_ar = $nom_ar; return $this; }

    public function getCode(): ?string { return $this->code; }
    public function setCode(string $code): static { $this->code = strtoupper($code); return $this; }
}
