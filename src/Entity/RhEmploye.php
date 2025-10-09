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
use App\Entity\Traits\IsActiveTrait;
use App\Repository\RhEmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RhEmployeRepository::class)]
#[ORM\Table(name: 'rh_employe')]
#[ApiResource(
    routePrefix: '/rh',
    normalizationContext: ['groups' => ['rh_employe:read']],
    denormalizationContext: ['groups' => ['rh_employe:write']],
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
    'nom_fr' => 'partial',
    'prenom_fr' => 'partial',
    'nom_ar' => 'partial',
    'prenom_ar' => 'partial',
    'mobile' => 'partial',
    'fix' => 'partial',
    'email' => 'partial',
    'cin' => 'exact'
])]
#[ApiFilter(DateFilter::class, properties: [
    'dateEmbauche' => DateFilter::EXCLUDE_NULL,
])]
class RhEmploye
{

    use IsActiveTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['rh_employe:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rh_employe:read', 'rh_employe:write','rh_conge:read'])]
    private ?string $nom_ar = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rh_employe:read', 'rh_employe:write','rh_conge:read'])]
    private ?string $prenom_ar = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['rh_employe:read', 'rh_employe:write','rh_conge:read'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['rh_employe:read', 'rh_employe:write','rh_conge:read'])]
    private ?string $prenom_fr = null;

    #[ORM\Column]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?\DateTimeImmutable $dateEmbauche = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?\DateTimeImmutable $dateFinEmbauche = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?int $heuresParSemaine = null;

    #[ORM\Column(length: 15)]
    #[Groups(['rh_employe:read', 'rh_employe:write','rh_conge:read'])]
    private ?string $mobile = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?string $fix = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?string $adresse = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?Civilite $civilite = null;

    #[ORM\Column(options: ['default' => 0.0])]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?float $congeDisponible = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?RhStatusFamille $statusFamille = null;

    /**
     * @var Collection<int, RhConge>
     */
    #[ORM\OneToMany(targetEntity: RhConge::class, mappedBy: 'employee', orphanRemoval: true)]
    private Collection $rhConges;

    #[ORM\Column(nullable: true)]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?int $nombreEnfants = null;

    #[ORM\Column(options: ['default' => 0.0])]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?float $salaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['rh_employe:read', 'rh_employe:write'])]
    private ?string $cin = null;

    public function __construct()
    {
        $this->rhConges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAr(): ?string
    {
        return $this->nom_ar;
    }

    public function setNomAr(string $nom_ar): static
    {
        $this->nom_ar = $nom_ar;

        return $this;
    }

    public function getPrenomAr(): ?string
    {
        return $this->prenom_ar;
    }

    public function setPrenomAr(string $prenom_ar): static
    {
        $this->prenom_ar = $prenom_ar;

        return $this;
    }

    public function getNomFr(): ?string
    {
        return $this->nom_fr;
    }

    public function setNomFr(?string $nom_fr): static
    {
        $this->nom_fr = $nom_fr;

        return $this;
    }

    public function getPrenomFr(): ?string
    {
        return $this->prenom_fr;
    }

    public function setPrenomFr(?string $prenom_fr): static
    {
        $this->prenom_fr = $prenom_fr;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeImmutable
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(\DateTimeImmutable $dateEmbauche): static
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    public function getDateFinEmbauche(): ?\DateTimeImmutable
    {
        return $this->dateFinEmbauche;
    }

    public function setDateFinEmbauche(?\DateTimeImmutable $dateFinEmbauche): static
    {
        $this->dateFinEmbauche = $dateFinEmbauche;

        return $this;
    }

    public function getHeuresParSemaine(): ?int
    {
        return $this->heuresParSemaine;
    }

    public function setHeuresParSemaine(?int $heuresParSemaine): static
    {
        $this->heuresParSemaine = $heuresParSemaine;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getFix(): ?string
    {
        return $this->fix;
    }

    public function setFix(?string $fix): static
    {
        $this->fix = $fix;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCivilite(): ?Civilite
    {
        return $this->civilite;
    }

    public function setCivilite(?Civilite $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getCongeDisponible(): ?float
    {
        return $this->congeDisponible;
    }

    public function setCongeDisponible(float $congeDisponible): static
    {
        $this->congeDisponible = $congeDisponible;

        return $this;
    }

    public function getStatusFamille(): ?RhStatusFamille
    {
        return $this->statusFamille;
    }

    public function setStatusFamille(?RhStatusFamille $statusFamille): static
    {
        $this->statusFamille = $statusFamille;

        return $this;
    }

    /**
     * @return Collection<int, RhConge>
     */
    public function getRhConges(): Collection
    {
        return $this->rhConges;
    }

    public function addRhConge(RhConge $rhConge): static
    {
        if (!$this->rhConges->contains($rhConge)) {
            $this->rhConges->add($rhConge);
            $rhConge->setEmployee($this);
        }

        return $this;
    }

    public function removeRhConge(RhConge $rhConge): static
    {
        if ($this->rhConges->removeElement($rhConge)) {
            // set the owning side to null (unless already changed)
            if ($rhConge->getEmployee() === $this) {
                $rhConge->setEmployee(null);
            }
        }

        return $this;
    }

    public function getNombreEnfants(): ?int
    {
        return $this->nombreEnfants;
    }

    public function setNombreEnfants(?int $nombreEnfants): static
    {
        $this->nombreEnfants = $nombreEnfants;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(float $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }
}
