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
use App\Repository\SiteReseauRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteReseauRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // GET    /api/site/site_reseaus/{id}
        new GetCollection(),  // GET    /api/site/site_reseaus
        new Post(),           // POST   /api/site/site_reseaus
        new Patch(),          // PATCH  /api/site/site_reseaus/{id}
    ],
    normalizationContext: [
        'groups' => ['sitereseau:read'],
        'skip_null_values' => false
    ],
    denormalizationContext: ['groups' => ['sitereseau:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
    'langue.code' => 'exact',
])]
class SiteReseau
{
    /// for section 1.1

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sitereseau:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitereseau:read','sitereseau:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    #[Groups(['sitereseau:read','sitereseau:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 10)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitereseau:read','sitereseau:write'])]
    #[Assert\Url(protocols: ['http','https'], message: 'Invalid URL.')]
    private ?string $link = null;

    use IsActiveTrait;   // exposes isActive with groups
    use HasLangueTrait;  // relation to Langue (write group in trait)

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): static { $this->name = $name; return $this; }

    public function getCode(): ?string { return $this->code; }
    public function setCode(string $code): static { $this->code = $code; return $this; }

    public function getLink(): ?string { return $this->link; }
    public function setLink(?string $link): static { $this->link = $link; return $this; }
}
