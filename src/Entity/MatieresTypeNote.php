<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Repository\MatieresTypeNoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MatieresTypeNoteRepository::class)]
#[ORM\UniqueConstraint(
    name: 'uniq_matiere_type_semestre',
    columns: ['matiere_id', 'type_note_id', 'semestre_id']
)]
#[UniqueEntity(
    fields: ['matiere', 'typeNote', 'semestre'],
    message: 'Cette combinaison (Matière, TypeNote, Semestre) existe déjà.'
)]
#[ApiResource(
    operations: [
        // ✅ GET all filtered by semestre
        new GetCollection(
            uriTemplate: '/matieres-type-note',
            normalizationContext: ['groups' => ['mtn:read']],
            paginationEnabled: true,
            paginationItemsPerPage: 10
        ),

        // ✅ GET single
        new Get(
            uriTemplate: '/matieres-type-note/{id}',
            normalizationContext: ['groups' => ['mtn:read']]
        ),

        // ✅ POST (admin only)
        new Post(
            uriTemplate: '/matieres-type-note',
            denormalizationContext: ['groups' => ['mtn:write']],
            normalizationContext: ['groups' => ['mtn:read']],
            security: "is_granted('ROLE_ADMIN')"
        ),

        // ✅ PATCH (admin only)
        new Patch(
            uriTemplate: '/matieres-type-note/{id}',
            denormalizationContext: ['groups' => ['mtn:update']],
            normalizationContext: ['groups' => ['mtn:read']],
            security: "is_granted('ROLE_ADMIN')"
        ),
    ],
    routePrefix: '/scolarite',
)]
#[ApiFilter(SearchFilter::class, properties: [
    'matiere.id' => 'exact',
    'semestre.id' => 'exact' // ✅ main filter
])]
class MatieresTypeNote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['mtn:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'matieresTypeNotes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['mtn:read', 'mtn:write'])]
    private ?Matieres $matiere = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['mtn:read', 'mtn:write'])]
    private ?TypeNote $typeNote = null;

    #[ORM\ManyToOne(inversedBy: 'matieresTypeNotes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['mtn:read', 'mtn:write'])]
    private ?Semestre $semestre = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['mtn:read', 'mtn:write', 'mtn:update'])]
    private ?float $coefficient = null;

    use \App\Entity\Traits\IsActiveTrait;

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

    public function getTypeNote(): ?TypeNote
    {
        return $this->typeNote;
    }

    public function setTypeNote(?TypeNote $typeNote): static
    {
        $this->typeNote = $typeNote;
        return $this;
    }

    public function getSemestre(): ?Semestre
    {
        return $this->semestre;
    }

    public function setSemestre(?Semestre $semestre): static
    {
        $this->semestre = $semestre;
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
