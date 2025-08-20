<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\SitePageGeneriqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SitePageGeneriqueRepository::class)]
#[UniqueEntity(
    fields: ['url'],
    message: 'This URL already exists. Please choose another.'
)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // GET    /api/site/site_page_generiques/{id}
        new GetCollection(),  // GET    /api/site/site_page_generiques
        new Post(),           // POST   /api/site/site_page_generiques
        new Patch(),          // PATCH  /api/site/site_page_generiques/{id}
    ],
    normalizationContext: [
        'groups' => ['sitepage:read'],
        'skip_null_values' => false
    ],
    denormalizationContext: ['groups' => ['sitepage:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
    'typePage' => 'exact',
])]
#[ORM\HasLifecycleCallbacks]
class SitePageGenerique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sitepage:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitepage:read','sitepage:write'])]
    #[Assert\Length(max: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['sitepage:read','sitepage:write'])]
    private ?string $contenu = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitepage:read','sitepage:write'])]
    #[Assert\Length(max: 255)]
    private ?string $linkImage = null;
    

    #[ORM\Column(length: 50)]
    #[Groups(['sitepage:read','sitepage:write'])]
    #[Assert\NotBlank(message: 'typePage is required.')]
    #[Assert\Length(max: 50)]
    private ?string $typePage = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sitepage:read','sitepage:write'])]
    private ?string $url = null;  /// exemple   /event/tavel


    use IsActiveTrait; // ensure this trait defines isActive with read/write groups
    use CreatedAtTrait;

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): static { $this->titre = $titre; return $this; }

    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(?string $contenu): static { $this->contenu = $contenu; return $this; }

    public function getLinkImage(): ?string { return $this->linkImage; }
    public function setLinkImage(?string $linkImage): static { $this->linkImage = $linkImage; return $this; }

    public function getTypePage(): ?string { return $this->typePage; }
    public function setTypePage(string $typePage): static { $this->typePage = $typePage; return $this; }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

}
