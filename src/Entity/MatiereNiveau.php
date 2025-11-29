<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\MatiereNiveauRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MatiereNiveauRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['matiereNiveau:read']],
    denormalizationContext: ['groups' => ['matiereNiveau:write']],
    paginationItemsPerPage: 20,
    paginationClientItemsPerPage: true,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Delete()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'matiere.id' => 'exact',
    'niveau.id' => 'exact',
    'anneeScolaire.id' => 'exact',
    'typeNote.id' => 'exact',
    'typeNote.code' => 'exact'
])]
class MatiereNiveau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['matiereNiveau:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['matiereNiveau:read', 'matiereNiveau:write'])]
    private ?Matieres $matiere = null;

    #[ORM\ManyToOne(inversedBy: 'niveauMatieres')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['matiereNiveau:read', 'matiereNiveau:write'])]
    private ?Niveau $niveau = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['matiereNiveau:read', 'matiereNiveau:write'])]
    private ?AnneeScolaireCourante $anneeScolaire = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['matiereNiveau:read', 'matiereNiveau:write'])]
    private ?TypeNote $typeNote = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['matiereNiveau:read', 'matiereNiveau:write'])]
    private ?string $formuleMoyenne = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['matiereNiveau:read', 'matiereNiveau:write'])]
    private ?float $coefficient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatiere(): ?Matieres
    {
        return $this->matiere;
    }

    public function setMatiere(?Matieres $matiere): static
    {
        $this->matiere = $matiere;
        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): static
    {
        $this->niveau = $niveau;
        return $this;
    }

    public function getAnneeScolaire(): ?AnneeScolaireCourante
    {
        return $this->anneeScolaire;
    }

    public function setAnneeScolaire(?AnneeScolaireCourante $anneeScolaire): static
    {
        $this->anneeScolaire = $anneeScolaire;
        return $this;
    }

    public function getTypeNote(): ?TypeNote
    {
        return $this->typeNote;
    }

    public function setTypeNote(?TypeNote $typeNote): static
    {
        $this->typeNote = $typeNote;
        return $this;
    }

    public function getFormuleMoyenne(): ?string
    {
        return $this->formuleMoyenne;
    }

    public function setFormuleMoyenne(?string $formuleMoyenne): static
    {
        $this->formuleMoyenne = $formuleMoyenne;
        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(?float $coefficient): static
    {
        $this->coefficient = $coefficient;
        return $this;
    }
}
