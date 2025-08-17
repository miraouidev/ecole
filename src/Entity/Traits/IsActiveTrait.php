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
    'siteaboutus:read','siteaboutus:write',
    'siteaboutticket:read','siteaboutticket:write',
    'sitewewhat:read', 'sitewewhat:write',
    'sitewewhatticket:read','sitewewhatticket:write',
    'siteourprogram:read','siteourprogram:write',
    'siteevent:read','siteevent:write',
    'siteeventticket:read','siteeventticket:write',
    'siteourteams:read','siteourteams:write',
    'sitefooter:read','sitefooter:write',
    'sitepage:read','sitepage:write'
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
