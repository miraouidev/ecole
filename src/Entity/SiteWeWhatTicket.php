<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\HasLangueTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\SiteWeWhatTicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteWeWhatTicketRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // /api/site/site_we_what_tickets/{id}
        new GetCollection(),  // /api/site/site_we_what_tickets
        new Post(),           // create
        new Patch(),          // partial update
    ],
    normalizationContext: [
        'groups' => ['sitewewhatticket:read'],
        'skip_null_values' => false
    ],
    denormalizationContext: ['groups' => ['sitewewhatticket:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
    'langue.code' => 'exact',
])]
class SiteWeWhatTicket
{
    /// for section 4.1  what we do

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sitewewhatticket:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitewewhatticket:read','sitewewhatticket:write'])]
    private ?string $icone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitewewhatticket:read','sitewewhatticket:write'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitewewhatticket:read','sitewewhatticket:write'])]
    private ?string $text = null;

    use IsActiveTrait;  // make sure the trait adds groups for this resource if you want it exposed
    use HasLangueTrait; // idem (usually write-only group on relation)

    public function getId(): ?int { return $this->id; }

    public function getIcone(): ?string { return $this->icone; }
    public function setIcone(?string $icone): static { $this->icone = $icone; return $this; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): static { $this->titre = $titre; return $this; }

    public function getText(): ?string { return $this->text; }
    public function setText(?string $text): static { $this->text = $text; return $this; }
}
