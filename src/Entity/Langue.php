<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LangueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: LangueRepository::class)]
#[ApiResource]
class Langue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    #[Groups(['siteheader:read','sitereseau:read','sitetopimage:read','siteaboutus:read','siteaboutticket:read','sitewewhat:read',
    'sitewewhatticket:read','siteourprogram:read','siteevent:read','siteeventticket:read','sitefooter:read'])]

    private ?string $code = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
