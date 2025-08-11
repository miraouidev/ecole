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
use App\Repository\SiteAboutUsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteAboutUsRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // GET    /api/site/site_about_us/{id}
        new GetCollection(),  // GET    /api/site/site_about_us
        new Post(),           // POST   /api/site/site_about_us
        new Patch(),          // PATCH  /api/site/site_about_us/{id}
    ],
    normalizationContext: [
        'groups' => ['siteaboutus:read'],
        'skip_null_values' => false
    ],
    denormalizationContext: ['groups' => ['siteaboutus:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
    'langue.code' => 'exact',
])]
class SiteAboutUs
{
    /// for section 3

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['siteaboutus:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['siteaboutus:read','siteaboutus:write'])]
    #[Assert\Url(protocols: ['http','https'], message: 'Invalid URL.')]
    private ?string $linkVideo = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['siteaboutus:read','siteaboutus:write'])]
    private ?string $titre = null; // normalized casing

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['siteaboutus:read','siteaboutus:write'])]
    private ?string $text = null;

    use IsActiveTrait;   // exposes bool isActive with groups (from the trait)
    use HasLangueTrait;  // relation to Langue (write group in the trait)

    public function getId(): ?int { return $this->id; }

    public function getLinkVideo(): ?string { return $this->linkVideo; }
    public function setLinkVideo(?string $linkVideo): static { $this->linkVideo = $linkVideo; return $this; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): static { $this->titre = $titre; return $this; }

    public function getText(): ?string { return $this->text; }
    public function setText(?string $text): static { $this->text = $text; return $this; }
}
