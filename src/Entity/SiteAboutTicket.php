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
use App\Repository\SiteAboutTicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteAboutTicketRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // /api/site/site_about_tickets/{id}
        new GetCollection(),  // /api/site/site_about_tickets
        new Post(),           // create
        new Patch(),          // partial update
    ],
    normalizationContext: [
        'groups' => ['siteaboutticket:read'],
        'skip_null_values' => false
    ],
    denormalizationContext: ['groups' => ['siteaboutticket:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
    'langue.code' => 'exact',
])]
class SiteAboutTicket
{
    /// for section 3.1

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['siteaboutticket:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['siteaboutticket:read','siteaboutticket:write'])]
    #[Assert\Length(max: 255)]
    private ?string $titre = null;

    use IsActiveTrait;   // ensure trait has groups for this resource if you want it exposed
    use HasLangueTrait;  // langue relation (usually write group in the trait)

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): static { $this->titre = $titre; return $this; }
}
