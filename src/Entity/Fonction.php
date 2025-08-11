<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\CodeNomTrait;
use App\Repository\FonctionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FonctionRepository::class)]
#[ApiResource]
class Fonction
{
       #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }
    use CodeNomTrait;

}
