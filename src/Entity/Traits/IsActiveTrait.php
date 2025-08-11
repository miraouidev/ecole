<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait IsActiveTrait
{
    #[ORM\Column(type: 'boolean')]
    #[Groups([
    'siteheader:read', 'siteheader:write',
    'sitereseau:read','sitereseau:write',
    'sitetopimage:read','sitetopimage:write',
    'siteaboutus:read','siteaboutus:write'
    ])]

    private ?bool $isActive = null;

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
