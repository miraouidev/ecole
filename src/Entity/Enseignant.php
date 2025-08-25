<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\PersonTrait;
use App\Repository\EnseignantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
#[ApiResource(
    routePrefix: '/scolaire',
    normalizationContext: ['groups' => ['enseignant:read']],
    denormalizationContext: ['groups' => ['enseignant:write']],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(denormalizationContext: ['groups' => ['enseignant:patch']])
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'nom_fr'    => 'partial',
    'prenom_fr' => 'partial',
    'cin'       => 'exact',
    'phone'     => 'partial',
    'mobile'    => 'partial',
    'isActive'  => 'exact'
])]
class Enseignant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['enseignant:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[Groups(['enseignant:read','enseignant:write','enseignant:patch'])]
    private ?Civilite $civilite = null;

    use PersonTrait;
    use IsActiveTrait;

    public function getId(): ?int { return $this->id; }

    public function getCivilite(): ?Civilite { return $this->civilite; }
    public function setCivilite(?Civilite $civilite): static {
        $this->civilite = $civilite;
        return $this;
    }
}
