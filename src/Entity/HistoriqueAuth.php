<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\HistoriqueAuthRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HistoriqueAuthRepository::class)]
#[ApiResource(
    order: ['id' => 'DESC'],
    routePrefix: '/admin',
    normalizationContext: ['groups' => ['historique:read'],'skip_null_values' => false],
    operations: [
        new \ApiPlatform\Metadata\GetCollection(), // liste avec filtres
        new \ApiPlatform\Metadata\Get(),           // détail
    ],
    paginationItemsPerPage: 10,
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: [
    'user.username' => 'partial', // filtre par username partiel
    'ip'            => 'partial', // LIKE %ip%
    'nameUser'      => 'partial'  // LIKE %nameUser%
])]
#[ApiFilter(BooleanFilter::class, properties: [
    'authOk',
    'isConnect'
])]
#[ApiFilter(DateFilter::class, properties: [
    'authAt'
])]
class HistoriqueAuth
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['historique:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['historique:read', 'historique:write'])]
    private ?\DateTimeImmutable $authAt = null;

    #[ORM\Column]
    #[Groups(['historique:read', 'historique:write'])]
    private ?bool $authOk = null;

    #[ORM\ManyToOne]
    #[Groups(['historique:read', 'historique:write'])]
    private ?User $user = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(['historique:read', 'historique:write'])]
    private ?string $ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['historique:read', 'historique:write'])]
    private ?string $nameUser = null;

    #[ORM\Column]
    #[Groups(['historique:read', 'historique:write'])]
    private ?bool $isConnect = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['historique:read', 'historique:write'])]
    private ?bool $isRefresh = null;

    public function getId(): ?int { return $this->id; }

    public function getAuthAt(): ?\DateTimeImmutable { return $this->authAt; }
    public function setAuthAt(\DateTimeImmutable $authAt): static {
        $this->authAt = $authAt;
        return $this;
    }

    public function isAuthOk(): ?bool { return $this->authOk; }
    public function setAuthOk(bool $authOk): static {
        $this->authOk = $authOk;
        return $this;
    }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static {
        $this->user = $user;
        return $this;
    }

    public function getIp(): ?string { return $this->ip; }
    public function setIp(?string $ip): static {
        $this->ip = $ip;
        return $this;
    }

    public function getNameUser(): ?string { return $this->nameUser; }
    public function setNameUser(?string $nameUser): static {
        $this->nameUser = $nameUser;
        return $this;
    }

    public function isConnect(): ?bool { return $this->isConnect; }
    public function getIsConnect(): ?bool { return $this->isConnect; }
    public function setIsConnect(bool $isConnect): static {
        $this->isConnect = $isConnect;
        return $this;
    }

    public function isRefresh(): ?bool
    {
        return $this->isRefresh;
    }

    public function getIsRefresh(): ?bool
    {
        return $this->isRefresh;
    }
    public function setIsRefresh(bool $isRefresh): static
    {
        $this->isRefresh = $isRefresh;

        return $this;
    }
}
