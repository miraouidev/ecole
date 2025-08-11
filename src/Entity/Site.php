<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Get;
use App\Repository\SiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
#[ApiResource(
    routePrefix: '/site',
    operations: [
        new Post(),   // POST /sites
        new Patch(),  // PATCH /sites/{id}
        new Get(),
    ],
    normalizationContext: ['groups' => ['site:read']],
    denormalizationContext: ['groups' => ['site:write']]
)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['site:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['site:read', 'site:write'])]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }
}
