<?php
namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Repository\MatieresRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MatieresRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['matiere:read']],
    denormalizationContext: ['groups' => ['matiere:write']],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true,
    operations: [new Get(), new GetCollection(), new Post(), new Patch()]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'nom_fr' => 'partial',
    'nom_ar' => 'partial',
    'niveau.code' => 'partial',
])]
class Matieres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['matiere:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['matiere:read', 'matiere:write'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['matiere:read', 'matiere:write'])]
    private ?string $nom_ar = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['matiere:read', 'matiere:write'])]
    private ?bool $isActive = true;

    #[ORM\ManyToOne]
    #[Groups(['matiere:read', 'matiere:write'])]
    private ?Niveau $niveau = null;

    public function getId(): ?int { return $this->id; }
    public function getNomFr(): ?string { return $this->nom_fr; }
    public function setNomFr(string $nom_fr): static { $this->nom_fr = $nom_fr; return $this; }
    public function getNomAr(): ?string { return $this->nom_ar; }
    public function setNomAr(string $nom_ar): static { $this->nom_ar = $nom_ar; return $this; }
    public function isActive(): ?bool { return $this->isActive; }
    public function getIsActive(): ?bool { return $this->isActive; }
    public function setIsActive(bool $isActive): static { $this->isActive = $isActive; return $this; }
    public function getNiveau(): ?Niveau { return $this->niveau; }
    public function setNiveau(?Niveau $niveau): static { $this->niveau = $niveau; return $this; }
}
