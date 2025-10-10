<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\RhCongeRepository;
use App\Validator\UniqueCongePeriode;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RhCongeRepository::class)]
#[ORM\Table(name: 'rh_conge')]
#[ApiResource(
    routePrefix: '/rh',
    normalizationContext: ['groups' => ['rh_conge:read']],
    denormalizationContext: ['groups' => ['rh_conge:write']],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'employee.nom_fr' => 'partial',
    'employee.id' => 'exact',
    'employee.prenom_fr' => 'partial',
    'employee.nom_ar' => 'partial',
    'employee.prenom_ar' => 'partial',
    'employee.mobile' => 'exact',
    'typeConge.libelle_fr' => 'partial',
    'statut' => 'exact'
])]
#[ApiFilter(DateFilter::class, properties: [
    'dateDebut' => DateFilter::EXCLUDE_NULL,
    'dateFin' => DateFilter::EXCLUDE_NULL
])]
#[UniqueCongePeriode]
class RhConge
{
    public const STATUT_EN_ATTENTE = 'EN_ATTENTE';
    public const STATUT_VALIDEE = 'VALIDEE';
    public const STATUT_REFUSEE = 'REFUSEE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rh_conge:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rhConges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rh_conge:read', 'rh_conge:write'])]
    private ?RhEmploye $employee = null;

    #[ORM\ManyToOne(inversedBy: 'rhConges')]
    #[Groups(['rh_conge:read', 'rh_conge:write'])]
    private ?RhTypeConge $typeConge = null;

    #[ORM\Column]
    #[Groups(['rh_conge:read', 'rh_conge:write'])]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column]
    #[Groups(['rh_conge:read', 'rh_conge:write'])]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column(length: 20, options: ['default' => 'EN_ATTENTE'])]
    #[Groups(['rh_conge:read', 'rh_conge:write'])]
    #[Assert\Choice(choices: [
        self::STATUT_EN_ATTENTE,
        self::STATUT_VALIDEE,
        self::STATUT_REFUSEE
    ], message: 'Statut invalide. Valeurs possibles : EN_ATTENTE, VALIDEE, REFUSEE.')]
    private ?string $statut = self::STATUT_EN_ATTENTE;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['rh_conge:read', 'rh_conge:write'])]
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?RhEmploye
    {
        return $this->employee;
    }

    public function setEmployee(?RhEmploye $employee): static
    {
        $this->employee = $employee;
        return $this;
    }

    public function getTypeConge(): ?RhTypeConge
    {
        return $this->typeConge;
    }

    public function setTypeConge(?RhTypeConge $typeConge): static
    {
        $this->typeConge = $typeConge;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }
}
