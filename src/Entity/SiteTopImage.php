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
use App\Repository\SiteTopImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteTopImageRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // GET    /api/site/site_top_images/{id}
        new GetCollection(),  // GET    /api/site/site_top_images
        new Post(),           // POST   /api/site/site_top_images
        new Patch(),          // PATCH  /api/site/site_top_images/{id}
    ],
    normalizationContext: [
        'groups' => ['sitetopimage:read'],
        'skip_null_values' => false
    ],
    denormalizationContext: ['groups' => ['sitetopimage:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
    'langue.code' => 'exact'
])]
class SiteTopImage
{
    /// for section 2
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sitetopimage:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitetopimage:read','sitetopimage:write'])]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitetopimage:read','sitetopimage:write'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitetopimage:read','sitetopimage:write'])]
    private ?string $text = null;

    use IsActiveTrait;   // exposes isActive with groups
    use HasLangueTrait;  // relation to Langue (with groups in trait)

    public function getId(): ?int { return $this->id; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): static { $this->image = $image; return $this; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): static { $this->titre = $titre; return $this; }

    public function getText(): ?string { return $this->text; }
    public function setText(?string $text): static { $this->text = $text; return $this; }
}
