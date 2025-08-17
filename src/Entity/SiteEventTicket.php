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
use App\Repository\SiteEventTicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteEventTicketRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Get(),            // GET    /api/site/site_event_tickets/{id}
        new GetCollection(),  // GET    /api/site/site_event_tickets
        new Post(),           // POST   /api/site/site_event_tickets
        new Patch(),          // PATCH  /api/site/site_event_tickets/{id}
    ],
    normalizationContext: [
        'groups' => ['siteeventticket:read'],
        'skip_null_values' => false
    ],
    denormalizationContext: ['groups' => ['siteeventticket:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: [
    'isActive' => 'exact',
    'langue.code' => 'exact',
])]
class SiteEventTicket
{
    /// for section 6.1

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['siteeventticket:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['siteeventticket:read','siteeventticket:write'])]
    #[Assert\Url(protocols: ['http','https'], message: 'Invalid URL.')]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['siteeventticket:read','siteeventticket:write'])]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeImmutable $date = null;   // expects "YYYY-MM-DD" in JSON

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['siteeventticket:read','siteeventticket:write'])]
    #[Assert\Length(max: 10)]
    private ?string $hourStart = null;          // e.g. "09:00"

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['siteeventticket:read','siteeventticket:write'])]
    #[Assert\Length(max: 10)]
    private ?string $hourEnd = null;            // e.g. "12:30"

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['siteeventticket:read','siteeventticket:write'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['siteeventticket:read','siteeventticket:write'])]
    private ?string $text = null;


    use IsActiveTrait;   // ensure the trait has serializer groups for this resource if you want it in read/write
    use HasLangueTrait;  // relation to Langue (typically write group in the trait)

    public function getId(): ?int { return $this->id; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): static { $this->image = $image; return $this; }

    public function getDate(): ?\DateTimeImmutable { return $this->date; }
    public function setDate(?\DateTimeImmutable $date): static { $this->date = $date; return $this; }

    public function getHourStart(): ?string { return $this->hourStart; }
    public function setHourStart(?string $hourStart): static { $this->hourStart = $hourStart; return $this; }

    public function getHourEnd(): ?string { return $this->hourEnd; }
    public function setHourEnd(?string $hourEnd): static { $this->hourEnd = $hourEnd; return $this; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): static { $this->titre = $titre; return $this; }

    public function getText(): ?string { return $this->text; }
    public function setText(?string $text): static { $this->text = $text; return $this; }

}
