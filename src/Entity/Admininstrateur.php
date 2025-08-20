<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\PersonTrait;
use App\Repository\AdmininstrateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdmininstrateurRepository::class)]
#[ApiResource(
    routePrefix: '/admin',
    normalizationContext: ['groups' => ['admin:read']],
    denormalizationContext: ['groups' => ['admin:write']],
     operations: [
        new \ApiPlatform\Metadata\Post(
            denormalizationContext: ['groups' => ['admin:write']], // ✅ user modifiable
        ),
        new \ApiPlatform\Metadata\Patch(
            denormalizationContext: ['groups' => ['admin:patch']], // 🚫 user NON modifiable
        ),
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\GetCollection()
    ],
    paginationItemsPerPage: 10,          // 10 résultats par page
    paginationClientItemsPerPage: true   // le client peut choisir via ?itemsPerPage=XX
)]
#[ApiFilter(SearchFilter::class, properties: [
    'nom' => 'partial',     // LIKE %nom%
    'prenom' => 'partial',  // LIKE %prenom%
    'cin' => 'exact',       // match exact
    'phone' => 'partial',   // LIKE %phone%
    'mobile' => 'partial'   // LIKE %mobile%
])]
class Admininstrateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['admin:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'admininstrateur', cascade: ['persist', 'remove'])]
    #[Groups(['admin:read', 'admin:write'])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['admin:read', 'admin:write','admin:patch'])]
    private ?Civilite $civilite = null;

    use PersonTrait;

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static {
        if ($user === null && $this->user !== null) {
            $this->user->setAdmininstrateur(null);
        }
        if ($user !== null && $user->getAdmininstrateur() !== $this) {
            $user->setAdmininstrateur($this);
        }
        $this->user = $user;
        return $this;
    }

    public function getCivilite(): ?Civilite { return $this->civilite; }
    public function setCivilite(?Civilite $civilite): static {
        $this->civilite = $civilite;
        return $this;
    }
}
