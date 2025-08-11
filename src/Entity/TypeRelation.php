<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\CodeNomTrait;
use App\Repository\TypeRelationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRelationRepository::class)]
#[ApiResource]
class TypeRelation
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
