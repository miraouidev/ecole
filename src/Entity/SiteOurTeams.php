<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\SiteOurTeamsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteOurTeamsRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // GET    /api/site/site_our_teams/{id}
        new GetCollection(),  // GET    /api/site/site_our_teams
        new Post(),           // POST   /api/site/site_our_teams
        new Patch(),          // PATCH  /api/site/site_our_teams/{id}
    ],
    normalizationContext: [
        'groups' => ['siteourteams:read'],
        'skip_null_values' => false
    ],
    denormalizationContext: ['groups' => ['siteourteams:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
])]
class SiteOurTeams
{
    /// for section 7

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['siteourteams:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['siteourteams:read','siteourteams:write'])]
    #[Assert\NotNull]
    private ?Enseignant $enseignant = null;

    #[ORM\Column]
    #[Groups(['siteourteams:read','siteourteams:write'])]
    #[Assert\NotNull]
    private ?int $ordre = null;

    use IsActiveTrait; // make sure the trait has groups for read/write of this resource

    public function getId(): ?int { return $this->id; }

    public function getEnseignant(): ?Enseignant { return $this->enseignant; }
    public function setEnseignant(Enseignant $enseignant): static { $this->enseignant = $enseignant; return $this; }

    public function getOrdre(): ?int { return $this->ordre; }
    public function setOrdre(int $ordre): static { $this->ordre = $ordre; return $this; }
}
