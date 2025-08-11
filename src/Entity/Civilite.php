<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\CodeNomTrait;
use App\Repository\CiviliteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CiviliteRepository::class)]
#[ApiResource]
class Civilite
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
