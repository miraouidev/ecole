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
use App\Repository\SiteHeaderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteHeaderRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // GET    /api/site/site_headers/{id}
        new GetCollection(),  // GET    /api/site/site_headers  (paginated)
        new Post(),           // POST   /api/site/site_headers
        new Patch(),          // PATCH  /api/site/site_headers/{id}
    ],
    normalizationContext: [
            'groups' => ['siteheader:read'],
            'skip_null_values' => false
        ],   
    denormalizationContext: ['groups' => ['siteheader:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
    'langue.code' => 'exact',   // filter by language code (ar, fr, en…)
])]
class SiteHeader
{
    ///  for section 1
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['siteheader:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['siteheader:read','siteheader:write'])]
    #[Assert\Length(max: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['siteheader:read','siteheader:write'])]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['siteheader:read','siteheader:write'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['siteheader:read','siteheader:write'])]
    private ?string $adresse = null;

    use IsActiveTrait;   // exposes bool isActive with groups
    use HasLangueTrait;  // ManyToOne Langue + groups

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): static { $this->phone = $phone; return $this; }

    public function getLogo(): ?string { return $this->logo; }
    public function setLogo(?string $logo): static { $this->logo = $logo; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): static { $this->email = $email; return $this; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): static { $this->adresse = $adresse; return $this; }

}
