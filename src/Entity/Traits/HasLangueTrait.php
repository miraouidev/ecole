<?php

namespace App\Entity\Traits;

use App\Entity\Langue;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait HasLangueTrait
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    // Expose in SiteHeader’s read/write groups (works because SiteHeader uses these groups)
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
        'sitefooter:read','sitefooter:write'
        ])]
    private ?Langue $langue = null;

    
    #[ORM\Column(length: 255, nullable: true)]
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
        'sitefooter:read','sitefooter:write'
        ])]
    private ?string $lienPage = null;

    public function getLangue(): ?Langue
    {
        return $this->langue;
    }

    public function setLangue(?Langue $langue): static
    {
        $this->langue = $langue;
        return $this;
    }

        public function getLienPage(): ?string
    {
        return $this->lienPage;
    }

    public function setLienPage(?string $lienPage): static
    {
        $this->lienPage = $lienPage;

        return $this;
    }
}
