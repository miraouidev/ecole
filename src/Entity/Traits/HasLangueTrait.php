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
        'siteaboutus:read','siteaboutus:write'
        ])]
    private ?Langue $langue = null;

    public function getLangue(): ?Langue
    {
        return $this->langue;
    }

    public function setLangue(?Langue $langue): static
    {
        $this->langue = $langue;
        return $this;
    }
}
