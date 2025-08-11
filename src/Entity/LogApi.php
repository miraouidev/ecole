<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LogApiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogApiRepository::class)]
#[ApiResource]
class LogApi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateAt = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $method = null;

    #[ORM\Column(nullable: true)]
    private ?array $contenu = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $utilisateur = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $device = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAt(): ?\DateTimeImmutable
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeImmutable $dateAt): static
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getContenu(): ?array
    {
        return $this->contenu;
    }

    public function setContenu(?array $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUtilisateur(): ?string
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?string $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(?string $device): static
    {
        $this->device = $device;

        return $this;
    }
}
