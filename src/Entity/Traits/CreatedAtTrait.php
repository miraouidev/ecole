<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CreatedAtTrait
{
    #[ORM\Column]
    #[Groups(['sitepage:read'])]
    private ?\DateTimeImmutable $createAt = null;

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;
        return $this;
    }

    #[ORM\PrePersist]
    public function initializeCreateAt(): void
    {
        $this->createAt = new \DateTimeImmutable();
    }
}
