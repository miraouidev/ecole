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
use App\Repository\SiteWeWhatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SiteWeWhatRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // GET    /api/site/site_we_whats/{id}
        new GetCollection(),  // GET    /api/site/site_we_whats
        new Post(),           // POST   /api/site/site_we_whats
        new Patch(),          // PATCH  /api/site/site_we_whats/{id}
    ],
    normalizationContext: [
        'groups' => ['sitewewhat:read'],
        'skip_null_values' => false
    ],
    denormalizationContext: ['groups' => ['sitewewhat:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
    'langue.code' => 'exact',
])]
class SiteWeWhat
{
    ///  for section 4  what we do
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sitewewhat:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitewewhat:read', 'sitewewhat:write'])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['sitewewhat:read', 'sitewewhat:write'])]
    private ?string $text = null;

    use IsActiveTrait;   // Make sure this trait includes the isActive property and getter/setter with serialization groups
    use HasLangueTrait;  // Ensure this trait maps langue relation with correct serialization groups

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): static { $this->titre = $titre; return $this; }

    public function getText(): ?string { return $this->text; }
    public function setText(?string $text): static { $this->text = $text; return $this; }
}
