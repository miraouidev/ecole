<?php

namespace App\Repository;

use App\Entity\MatiereNiveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MatiereNiveau>
 */
class MatiereNiveauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatiereNiveau::class);
    }

    /**
     * Find configurations by academic year, subject and level
     */
    public function findByAnneeMatiereNiveau(
        int $anneeId,
        int $matiereId,
        int $niveauId
    ): array {
        return $this->createQueryBuilder('mn')
            ->andWhere('mn.anneeScolaire = :anneeId')
            ->andWhere('mn.matiere = :matiereId')
            ->andWhere('mn.niveau = :niveauId')
            ->setParameter('anneeId', $anneeId)
            ->setParameter('matiereId', $matiereId)
            ->setParameter('niveauId', $niveauId)
            ->orderBy('mn.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
