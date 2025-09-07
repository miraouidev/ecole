<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Eleve;
use App\Entity\ParentEleveRelation;
use App\Repository\ParentProfileRepository;
use App\Repository\TypeRelationRepository;
use Doctrine\ORM\EntityManagerInterface;

class EleveProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ParentProfileRepository $parentRepo,
        private TypeRelationRepository $typeRelationRepo
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Eleve) {
            return $data;
        }

        $requestData = $context['request']->toArray();

        if (isset($requestData['parentEleveRelations']) && is_array($requestData['parentEleveRelations'])) {
            foreach ($requestData['parentEleveRelations'] as $relData) {
                $parent = isset($relData['parent'])
                    ? $this->parentRepo->find($this->extractIdFromIri($relData['parent']))
                    : null;

                $type = isset($relData['typeRelation'])
                    ? $this->typeRelationRepo->find($this->extractIdFromIri($relData['typeRelation']))
                    : null;

                if ($parent) {
                    $relation = new ParentEleveRelation();
                    $relation->setParent($parent);
                    $relation->setTypeRelation($type);
                    $relation->setEleve($data);

                    $this->em->persist($relation);
                }else{
                    throw new \Exception("Parent not found for the given ID.");
                }
            }
        }

        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }

    private function extractIdFromIri(string $iri): int
    {
        return (int) substr($iri, strrpos($iri, '/') + 1);
    }
}
