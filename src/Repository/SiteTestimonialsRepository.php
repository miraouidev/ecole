<?php

namespace App\Repository;

use App\Entity\SiteTestimonials;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SiteTestimonials>
 */
class SiteTestimonialsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiteTestimonials::class);
    }

}
