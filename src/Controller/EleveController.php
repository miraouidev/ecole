<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Groupe;
use App\Entity\ParentEleveRelation;
use App\Entity\ParentProfile;
use App\Entity\TypeRelation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/api/scolaire/eleves')]
class EleveController extends AbstractController
{
     public function __construct(
        private EntityManagerInterface $em,
        private CacheInterface $cache
    ) {}

    #[Route('/by-groupe/{id}', name: 'eleve_by_groupe', methods: ['GET'])]
    public function listByGroupe(int $id): JsonResponse
    {
        $cacheKey = "eleves_groupe_$id";

        $data = $this->cache->get($cacheKey, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(360000); // ⏳ 1h de cache

            $groupe = $this->em->getRepository(Groupe::class)->find($id);
            if (!$groupe) {
                return [
                    '@context' => '/api/contexts/Error',
                    '@type' => 'Error',
                    'title' => 'Not Found',
                    'detail' => "Groupe introuvable",
                    'status' => 404
                ];
            }

            $eleves = $this->em->getRepository(Eleve::class)->findBy(['groupe' => $groupe]);

            $members = [];
            foreach ($eleves as $eleve) {
                $relations = [];
                foreach ($eleve->getParentEleveRelations() as $rel) {
                    $relations[] = [
                        '@id' => "/api/parent_eleve_relations/".$rel->getId(),
                        '@type' => 'ParentEleveRelation',
                        'parent' => [
                            '@id' => "/api/scolaire/parent_profiles/".$rel->getParent()->getId(),
                            '@type' => 'ParentProfile',
                            'civilite' => [
                                '@id' => "/api/annexe/civilites/".$rel->getParent()->getCivilite()->getId(),
                                '@type' => 'Civilite',
                                'nom_fr' => $rel->getParent()->getCivilite()->getNomFr(),
                                'nom_ar' => $rel->getParent()->getCivilite()->getNomAr(),
                                'code'   => $rel->getParent()->getCivilite()->getCode(),
                            ],
                            'nom_fr'    => $rel->getParent()->getNomFr(),
                            'nom_ar'    => $rel->getParent()->getNomAr(),
                            'prenom_fr' => $rel->getParent()->getPrenomFr(),
                            'prenom_ar' => $rel->getParent()->getPrenomAr(),
                            'phone'     => $rel->getParent()->getPhone(),
                            'mobile'    => $rel->getParent()->getMobile(),
                        ],
                        'typeRelation' => [
                            '@id' => "/api/annexe/type_relations/".$rel->getTypeRelation()->getId(),
                            '@type' => 'TypeRelation',
                            'nom_fr' => $rel->getTypeRelation()->getNomFr(),
                            'nom_ar' => $rel->getTypeRelation()->getNomAr(),
                            'code'   => $rel->getTypeRelation()->getCode(),
                        ]
                    ];
                }

                $members[] = [
                    '@id' => "/api/scolaire/eleves/".$eleve->getId(),
                    '@type' => 'Eleve',
                    'id' => $eleve->getId(),
                    'nom_fr' => $eleve->getNomFr(),
                    'nom_ar' => $eleve->getNomAr(),
                    'prenom_fr' => $eleve->getPrenomFr(),
                    'prenom_ar' => $eleve->getPrenomAr(),
                    'dateNai' => $eleve->getDateNai()?->format('Y-m-d\TH:i:sP'),
                    
                    'parentEleveRelations' => $relations
                ];
            }

            return [
                '@context' => '/api/contexts/Eleve',
                '@id' => '/api/scolaire/eleves',
                '@type' => 'Collection',
                'totalItems' => count($members),
                'member' => $members,
                'view' => [
                    '@id' => "/api/scolaire/eleves?groupe.id=$id",
                    '@type' => 'PartialCollectionView'
                ],
                'search' => [
                    '@type' => 'IriTemplate',
                    'template' => '/api/scolaire/eleves{?nom_fr,nom_ar,prenom_fr,prenom_ar,groupe.id,groupe.id[]}',
                    'variableRepresentation' => 'BasicRepresentation',
                    'mapping' => [
                        ['@type' => 'IriTemplateMapping', 'variable' => 'nom_fr', 'property' => 'nom_fr', 'required' => false],
                        ['@type' => 'IriTemplateMapping', 'variable' => 'nom_ar', 'property' => 'nom_ar', 'required' => false],
                        ['@type' => 'IriTemplateMapping', 'variable' => 'prenom_fr', 'property' => 'prenom_fr', 'required' => false],
                        ['@type' => 'IriTemplateMapping', 'variable' => 'prenom_ar', 'property' => 'prenom_ar', 'required' => false],
                        ['@type' => 'IriTemplateMapping', 'variable' => 'groupe.id', 'property' => 'groupe.id', 'required' => false],
                        ['@type' => 'IriTemplateMapping', 'variable' => 'groupe.id[]', 'property' => 'groupe.id', 'required' => false],
                    ]
                ]
            ];
        });

        return new JsonResponse($data);
    }

    private function getIdFromIri(string $iri): ?int
    {
        return preg_match('#/(\d+)$#', $iri, $matches) ? (int)$matches[1] : null;
    }

    // 🔹 1. Changer le groupe
    #[Route('/{id}/change-groupe', name: 'eleve_change_groupe', methods: ['PATCH'])]
    public function changeGroupe(int $id, Request $request): JsonResponse
    {
        $eleve = $this->em->getRepository(Eleve::class)->find($id);
        if (!$eleve) {
            return $this->json(['error' => 'Élève introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['groupe'])) {
            return $this->json(['error' => 'Paramètre "groupe" requis'], 400);
        }

        $groupeId = $this->getIdFromIri($data['groupe']);
        $groupe = $this->em->getRepository(Groupe::class)->find($groupeId);

        if (!$groupe) {
            return $this->json(['error' => 'Groupe introuvable'], 404);
        }

        $eleve->setGroupe($groupe);
        $this->em->flush();

        return $this->json(['success' => "Élève affecté au groupe ".$groupe->getNomFr()]);
    }

    // 🔹 2. Ajouter une relation parent/élève
    #[Route('/{id}/add-relation', name: 'eleve_add_relation', methods: ['POST'])]
    public function addRelation(int $id, Request $request): JsonResponse
    {
        $eleve = $this->em->getRepository(Eleve::class)->find($id);
        if (!$eleve) {
            return $this->json(['error' => 'Élève introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['parent'], $data['typeRelation'])) {
            return $this->json(['error' => 'Champs requis: parent, typeRelation'], 400);
        }

        $parentId = $this->getIdFromIri($data['parent']);
        $relationId = $this->getIdFromIri($data['typeRelation']);

        $parent = $this->em->getRepository(ParentProfile::class)->find($parentId);
        $typeRelation = $this->em->getRepository(TypeRelation::class)->find($relationId);

        if (!$parent || !$typeRelation) {
            return $this->json(['error' => 'Parent ou TypeRelation introuvable'], 404);
        }

        $relation = new ParentEleveRelation();
        $relation->setEleve($eleve);
        $relation->setParent($parent);
        $relation->setTypeRelation($typeRelation);

        $this->em->persist($relation);
        $this->em->flush();

        return $this->json(['success' => 'Relation ajoutée avec succès']);
    }

    // 🔹 3. Supprimer une relation
    #[Route('/{id}/remove-relation/{relationId}', name: 'eleve_remove_relation', methods: ['DELETE'])]
    public function removeRelation(int $id, int $relationId): JsonResponse
    {
        $eleve = $this->em->getRepository(Eleve::class)->find($id);
        if (!$eleve) {
            return $this->json(['error' => 'Élève introuvable'], 404);
        }

        $relation = $this->em->getRepository(ParentEleveRelation::class)->find($relationId);
        if (!$relation || $relation->getEleve()->getId() !== $id) {
            return $this->json(['error' => 'Relation introuvable pour cet élève'], 404);
        }

        $this->em->remove($relation);
        $this->em->flush();

        return $this->json(['success' => 'Relation supprimée avec succès']);
    }
}
