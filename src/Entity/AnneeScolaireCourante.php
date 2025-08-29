<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Entity\Traits\IsActiveTrait;
use App\Repository\AnneeScolaireCouranteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnneeScolaireCouranteRepository::class)]
#[ApiResource(
    routePrefix: '/admin',
    order: ['id' => 'DESC'],
    normalizationContext: ['groups' => ['annee:read']],
    denormalizationContext: ['groups' => ['annee:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(denormalizationContext: ['groups' => ['annee:patch']])
    ]
)]
class AnneeScolaireCourante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['annee:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['annee:read','annee:write','annee:patch'])]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups(['annee:read','annee:write','annee:patch'])]
    private ?\DateTime $dateDebut = null;

    #[ORM\Column]
    #[Groups(['annee:read','annee:write','annee:patch'])]
    private ?\DateTime $dateFin = null;

    use IsActiveTrait;
    /**
     * @var Collection<int, Groupe>
     */
    #[ORM\OneToMany(targetEntity: Groupe::class, mappedBy: 'anneeScolaire')]
    #[Groups(['annee:read'])]
    private Collection $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getDateDebut(): ?\DateTime { return $this->dateDebut; }
    public function setDateDebut(\DateTime $dateDebut): static { $this->dateDebut = $dateDebut; return $this; }

    public function getDateFin(): ?\DateTime { return $this->dateFin; }
    public function setDateFin(\DateTime $dateFin): static { $this->dateFin = $dateFin; return $this; }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection { return $this->groupes; }

    public function addGroupe(Groupe $groupe): static {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->setAnneeScolaire($this);
        }
        return $this;
    }

    public function removeGroupe(Groupe $groupe): static {
        if ($this->groupes->removeElement($groupe)) {
            if ($groupe->getAnneeScolaire() === $this) {
                $groupe->setAnneeScolaire(null);
            }
        }
        return $this;
    }
}
