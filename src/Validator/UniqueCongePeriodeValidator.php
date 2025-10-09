<?php

namespace App\Validator;

use App\Entity\RhConge;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueCongePeriodeValidator extends ConstraintValidator
{
    public function __construct(private EntityManagerInterface $em) {}

    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof RhConge) {
            return;
        }

        if (!$value->getEmployee() || !$value->getDateDebut() || !$value->getDateFin()) {
            return;
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('c')
            ->from(RhConge::class, 'c')
            ->where('c.employee = :employee')
            ->andWhere('(:debut BETWEEN c.dateDebut AND c.dateFin)
                     OR (:fin BETWEEN c.dateDebut AND c.dateFin)
                     OR (c.dateDebut BETWEEN :debut AND :fin)')
            ->setParameter('employee', $value->getEmployee())
            ->setParameter('debut', $value->getDateDebut())
            ->setParameter('fin', $value->getDateFin());

        if ($value->getId()) {
            $qb->andWhere('c.id != :id')->setParameter('id', $value->getId());
        }

        $conflicts = $qb->getQuery()->getResult();

        if (!empty($conflicts)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('dateDebut')
                ->addViolation();
        }
    }
}
