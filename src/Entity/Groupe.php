<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['groupe:read']],
    denormalizationContext: ['groups' => ['groupe:write']],
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
    'nom_ar' => 'partial',
    'niveau.id' => 'exact',
    'anneeScolaire.id' => 'exact'
])]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['groupe:read','eleve:read','parent:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['groupe:read','groupe:write','eleve:read','parent:read'])]
    private ?string $nom_fr = null;

    #[ORM\Column(length: 100)]
    #[Groups(['groupe:read','groupe:write','eleve:read','parent:read'])]
    private ?string $nom_ar = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['groupe:read','groupe:write'])]
    private ?Niveau $niveau = null;

    #[ORM\ManyToOne(inversedBy: 'groupes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['groupe:read','groupe:write'])]
    private ?AnneeScolaireCourante $anneeScolaire = null;

    /**
     * @var Collection<int, Eleve>
     */
    #[ORM\OneToMany(targetEntity: Eleve::class, mappedBy: 'groupe')]
    private Collection $eleves;

    /**
     * @var Collection<int, MatiereClasseProf>
     */
    #[ORM\OneToMany(targetEntity: MatiereClasseProf::class, mappedBy: 'groupe')]
    private Collection $matiereClasseProfs;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $this->matiereClasseProfs = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNomFr(): ?string { return $this->nom_fr; }
    public function setNomFr(string $nom_fr): static { $this->nom_fr = $nom_fr; return $this; }

    public function getNomAr(): ?string { return $this->nom_ar; }
    public function setNomAr(string $nom_ar): static { $this->nom_ar = $nom_ar; return $this; }

    public function getNiveau(): ?Niveau { return $this->niveau; }
    public function setNiveau(?Niveau $niveau): static { $this->niveau = $niveau; return $this; }

    public function getAnneeScolaire(): ?AnneeScolaireCourante { return $this->anneeScolaire; }
    public function setAnneeScolaire(?AnneeScolaireCourante $anneeScolaire): static { $this->anneeScolaire = $anneeScolaire; return $this; }

    /**
     * @return Collection<int, Eleve>
     */
    public function getEleves(): Collection { return $this->eleves; }

    /**
     * @return Collection<int, MatiereClasseProf>
     */
    public function getMatiereClasseProfs(): Collection { return $this->matiereClasseProfs; }
}
