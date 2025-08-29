<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\State\UserPasswordProcessor;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields: ['username'],
    message: 'This username already exists. Please choose another.'
)]
#[UniqueEntity(
    fields: ['email'],
    message: 'This email already exists. Please choose another.'
)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource( // 🛠️ admin management
    routePrefix: '/admin',
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    operations: [
        new Get(security: "is_granted('ROLE_ADMIN')"),
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new Post(processor: UserPasswordProcessor::class, security: "is_granted('ROLE_ADMIN')"),
        new Patch(
            denormalizationContext: ['groups' => ['user:patch']],
            
        )
    ],
    paginationItemsPerPage: 5,
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: [
    'type' => 'exact',      // admin, parent, prof
    'username' => 'partial' // LIKE search
])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'admin:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:read', 'user:write', 'admin:read','parent:read','historique:read'])]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write', 'admin:read','user:patch','parent:read','parent:patch','historique:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[Groups(['user:read', 'user:write', 'user:patch'])] // allow PATCH only for this group
    private ?string $type = null; // admin, user, parent

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:write'])] // ⚠️ visible seulement à la création
    private ?string $password = null;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    #[Groups(['user:read', 'user:write'])]
    private ?Admininstrateur $admininstrateur = null;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?ParentProfile $parent = null;

    use IsActiveTrait;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        //$roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // nothing
    }

    public function getAdmininstrateur(): ?Admininstrateur
    {
        return $this->admininstrateur;
    }

    public function setAdmininstrateur(?Admininstrateur $admininstrateur): static
    {
        $this->admininstrateur = $admininstrateur;

        return $this;
    }

    public function getParent(): ?ParentProfile
    {
        return $this->parent;
    }

    public function setParent(?ParentProfile $parent): static
    {
        $this->parent = $parent;

        return $this;
    }
}
