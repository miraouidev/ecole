<?php

namespace App\Controller;

use App\Entity\ParentProfile;
use App\Entity\ParentEleveRelation;
use App\Entity\Eleve;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/api/scolaire/parent_profiles')]
class ParentController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private CacheInterface $cache
    ) {}

    #[Route('/{id}/eleve', name: 'parent_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $cacheKey = "parent_profile_$id";

        $data = $this->cache->get($cacheKey, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(3600); // 1h de cache

            $parent = $this->em->getRepository(ParentProfile::class)->find($id);
            if (!$parent) {
                return [
                    '@context' => '/api/contexts/Error',
                    '@type' => 'Error',
                    'title' => 'Not Found',
                    'detail' => "Parent introuvable",
                    'status' => 404
                ];
            }

            // relations parent-enfant
            $relations = [];
            foreach ($parent->getParentEleveRelations() as $rel) {
                $eleve = $rel->getEleve();
                $groupe = $eleve->getGroupe();

                $relations[] = [
                    '@id' => "/api/parent_eleve_relations/".$rel->getId(),
                    '@type' => 'ParentEleveRelation',
                    'eleve' => [
                        '@id' => "/api/scolaire/eleves/".$eleve->getId(),
                        '@type' => 'Eleve',
                        'nom_fr' => $eleve->getNomFr(),
                        'nom_ar' => $eleve->getNomAr(),
                        'prenom_fr' => $eleve->getPrenomFr(),
                        'prenom_ar' => $eleve->getPrenomAr(),
                        'dateNai' => $eleve->getDateNai()?->format('Y-m-d\TH:i:sP'),
                        'groupe' => $groupe ? [
                            '@id' => "/api/scolaire/groupes/".$groupe->getId(),
                            '@type' => 'Groupe',
                            'id' => $groupe->getId(),
                            'nom_fr' => $groupe->getNomFr(),
                            'nom_ar' => $groupe->getNomAr(), // ⚠️ si tu ajoutes nom_ar
                        ] : null
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

            $user = $parent->getUser();

            return [
                '@context' => '/api/contexts/ParentProfile',
                '@id' => "/api/scolaire/parent_profiles/".$parent->getId(),
                '@type' => 'ParentProfile',
                'id' => $parent->getId(),
                'user' => $user ? [
                    '@id' => "/api/admin/users/".$user->getId(),
                    '@type' => 'User',
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail()
                ] : null,
                'parentEleveRelations' => $relations,
                'civilite' => $parent->getCivilite() ? [
                    '@id' => "/api/annexe/civilites/".$parent->getCivilite()->getId(),
                    '@type' => 'Civilite',
                    'id' => $parent->getCivilite()->getId(),
                    'nom_fr' => $parent->getCivilite()->getNomFr(),
                    'nom_ar' => $parent->getCivilite()->getNomAr(),
                    'code'   => $parent->getCivilite()->getCode()
                ] : null,
                'nom_fr' => $parent->getNomFr(),
                'nom_ar' => $parent->getNomAr(),
                'prenom_fr' => $parent->getPrenomFr(),
                'prenom_ar' => $parent->getPrenomAr(),
                'dateNai' => $parent->getDateNai()?->format('Y-m-d'),
                'cin' => $parent->getCin(),
                'phone' => $parent->getPhone(),
                'mobile' => $parent->getMobile(),
                'adresse' => $parent->getAdresse(),
            ];
        });

        return new JsonResponse($data);
    }
}
