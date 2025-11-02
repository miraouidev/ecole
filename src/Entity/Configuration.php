<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ConfigurationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConfigurationRepository::class)]
#[ApiResource(
    // 🛠️ Admin management
    routePrefix: '/admin',
    normalizationContext: ['groups' => ['configuration:read']],
    denormalizationContext: ['groups' => ['configuration:write']],
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ADMIN')",
            name: 'admin_get_configuration_collection'
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')",
            denormalizationContext: ['groups' => ['configuration:patch']],
            name: 'admin_patch_configuration'
        ),
    ],
    paginationEnabled: false
)]
class Configuration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['configuration:read'])]
    private ?int $id = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['configuration:read', 'configuration:write', 'configuration:patch'])]
    private ?bool $isModifierTypeNote = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isModifierTypeNote(): ?bool
    {
        return $this->isModifierTypeNote;
    }

    public function getIsModifierTypeNote(): ?bool
    {
        return $this->isModifierTypeNote;
    }

    public function setIsModifierTypeNote(bool $isModifierTypeNote): static
    {
        $this->isModifierTypeNote = $isModifierTypeNote;
        return $this;
    }
}
