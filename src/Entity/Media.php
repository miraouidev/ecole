<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\MediaController;
use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),           // GET /media
        new Delete(),                  // DELETE /media/{id} — physical file removal handled by processor (below)
        // Custom multipart upload endpoint:
    ],
    normalizationContext: ['groups' => ['media:read']],
    denormalizationContext: ['groups' => ['media:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]
#[ApiFilter(SearchFilter::class, properties: ['dossier' => 'exact'])]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['media:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['media:read', 'media:write'])]
    private ?string $name = null;

    // Absolute/relative URL or path to the stored file
    #[ORM\Column(length: 255)]
    #[Groups(['media:read'])]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['media:read', 'media:write'])]
    private ?string $info = null;

    // e.g. subfolder name
    #[ORM\Column(length: 255)]
    #[Groups(['media:read', 'media:write'])]
    private ?string $dossier = null;

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): static { $this->name = $name; return $this; }

    public function getLink(): ?string { return $this->link; }
    public function setLink(string $link): static { $this->link = $link; return $this; }

    public function getInfo(): ?string { return $this->info; }
    public function setInfo(?string $info): static { $this->info = $info; return $this; }

    public function getDossier(): ?string { return $this->dossier; }
    public function setDossier(string $dossier): static { $this->dossier = $dossier; return $this; }
}
