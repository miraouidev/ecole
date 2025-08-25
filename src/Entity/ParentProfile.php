<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Entity\Traits\PersonTrait;
use App\Repository\ParentProfileRepository;
use App\State\ParentProfileProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ParentProfileRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['parent:read']],
    denormalizationContext: ['groups' => ['parent:write']],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(processor: ParentProfileProcessor::class),
        new Patch()
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'nom_fr'     => 'partial',
    'nom_ar'     => 'partial',
    'prenom_fr'  => 'partial',
    'prenom_ar'  => 'partial',
    'cin'        => 'exact',
    'mobile'     => 'partial',
    'user.isActive' => 'exact'  // ✅ recherche parent actif/inactif
])]
class ParentProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['parent:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'parent', cascade: ['persist', 'remove'])]
    #[Groups(['parent:read'])]
    private ?User $user = null;

    /**
     * @var Collection<int, ParentEleveRelation>
     */
    #[ORM\OneToMany(targetEntity: ParentEleveRelation::class, mappedBy: 'parent')]
    #[Groups(['parent:read'])]
    private Collection $parentEleveRelations;

    #[ORM\ManyToOne]
    #[Groups(['parent:read','parent:write','eleve:read'])]
    private ?Civilite $civilite = null;

    use PersonTrait;

    public function __construct()
    {
        $this->parentEleveRelations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        if ($user === null && $this->user !== null) {
            $this->user->setParent(null);
        }

        if ($user !== null && $user->getParent() !== $this) {
            $user->setParent($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, ParentEleveRelation>
     */
    public function getParentEleveRelations(): Collection
    {
        return $this->parentEleveRelations;
    }

    public function addParentEleveRelation(ParentEleveRelation $parentEleveRelation): static
    {
        if (!$this->parentEleveRelations->contains($parentEleveRelation)) {
            $this->parentEleveRelations->add($parentEleveRelation);
            $parentEleveRelation->setParent($this);
        }

        return $this;
    }

    public function removeParentEleveRelation(ParentEleveRelation $parentEleveRelation): static
    {
        if ($this->parentEleveRelations->removeElement($parentEleveRelation)) {
            if ($parentEleveRelation->getParent() === $this) {
                $parentEleveRelation->setParent(null);
            }
        }

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
}
