<?php

namespace App\Controller;

use App\Entity\AnneeScolaireCourante;
use App\Entity\Configuration;
use App\Entity\Eleve;
use App\Entity\Groupe;
use App\Entity\ParentEleveRelation;
use App\Entity\ParentProfile;
use App\Entity\Scolarite;
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

    #[Route('/by-groupe/{id}/mini/{miniNumber}', name: 'eleve_by_groupe', methods: ['GET'])]
    public function listByGroupe(int $id,int $miniNumber): JsonResponse
    {
        $cacheKey = "eleves_groupe_{$id}_mini_{$miniNumber}";

        $data = $this->cache->get($cacheKey, function (ItemInterface $item) use ($id, $miniNumber) {

            $item->expiresAfter(360000); //  1h de cache

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

            // Déterminer la source des élèves : groupe complet ou mini-groupe
            $elevesRepo = $this->em->getRepository(Eleve::class);
            $miniGroupes = $groupe->getGroupeMinis()->toArray();
            $eleves = [];

            if ($miniNumber === 0 || count($miniGroupes) === 0) {
                // Tous les élèves du groupe
                $eleves = $elevesRepo->findBy(['groupe' => $groupe]);
            } else {
                $targetMini = $miniGroupes[$miniNumber - 1] ?? null;
                if ($targetMini) {
                    $eleves = $elevesRepo->findBy(['groupeMini' => $targetMini]);
                } else {
                    // Si index mini invalide
                    $eleves = $elevesRepo->findBy(['groupe' => $groupe]);
                }
            }
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

         // Check configuration
        $config = $this->em->getRepository(Configuration::class)->findOneBy([]);
        if (!$config || !$config->isChangeGroup()) {
            return new JsonResponse(['alert' => 'Merci d’activer Change Class dans la configuration'], 400);
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
        if($groupe->getGroupeMinis()->count()>0 ){
            $eleve->setGroupeMini($groupe->getGroupeMinis()->first()); 
        }else{
            $eleve->setGroupeMini(null);
        }
        
            // 2. Find active academic year
        $annee = $this->em->getRepository(AnneeScolaireCourante::class)
            ->findOneBy(['isActive' => true]);
        if (!$annee) {
            return $this->json(['error' => 'Aucune année scolaire active trouvée'], 400);
        }

         // 3. Handle existing scolarité(s)
        foreach ($eleve->getScolarites() as $oldScol) {
            if ($oldScol->getAnnee() === $annee) {
                // Option 1: Delete old record
                $this->em->remove($oldScol);

                // Option 2 (alternative): just deactivate instead
                // $oldScol->setIsActive(false);
            }
        }

        // 4. Create new Scolarite relation
        $scolarite = new Scolarite();
        $scolarite->setEleve($eleve);
        $scolarite->setGroupe($groupe);
        $scolarite->setAnnee($annee);
        $eleve->addScolarite($scolarite);
        $this->em->persist($scolarite);


        $this->em->flush();

        return $this->json(['success' => "Élève affecté au groupe ".$groupe->getNomFr()]);
    }
    
    #[Route('/cache/clear', name: 'eleve_clear_cache', methods: ['GET'])]
    public function setNewData(): JsonResponse
    {
        // Get all groups
        $groupes = $this->em->getRepository(Groupe::class)->findAll();

        $deleted = 0;
        foreach ($groupes as $groupe) {
            $cacheKey = "eleves_groupe_" . $groupe->getId();
            if ($this->cache->delete($cacheKey)) {
                $deleted++;
            }
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => "Cache cleared for {$deleted} groupes"
        ]);
    }

    #[Route('/create', name: 'eleve_create', methods: ['POST'])]
    public function createEleve(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // 1. Validate payload
        foreach (['nom_fr','prenom_fr','nom_ar','prenom_ar','dateNai','groupe','parentEleveRelations'] as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Champ manquant : $field"], 400);
            }
        }

        // 2. Resolve groupe
        $groupeId = $this->getIdFromIri($data['groupe']);
        $groupe = $this->em->getRepository(Groupe::class)->find($groupeId);
        if (!$groupe) {
            return $this->json(['error' => 'Groupe introuvable'], 404);
        }

        // 3. Find active school year
        $annee = $this->em->getRepository(AnneeScolaireCourante::class)->findOneBy(['isActive' => true]);
        if (!$annee) {
            return $this->json(['error' => 'Aucune année scolaire active trouvée'], 400);
        }

        // 4. Create Eleve
        $eleve = new Eleve();
        $eleve->setNomFr($data['nom_fr']);
        $eleve->setPrenomFr($data['prenom_fr']);
        $eleve->setNomAr($data['nom_ar']);
        $eleve->setPrenomAr($data['prenom_ar']);
        $eleve->setDateNai(new \DateTimeImmutable($data['dateNai']));
        $eleve->setGroupe($groupe);

        // 5. Add parent relations
        foreach ($data['parentEleveRelations'] as $relationData) {
            $parentId = $this->getIdFromIri($relationData['parent'] ?? '');
            $typeId   = $this->getIdFromIri($relationData['typeRelation'] ?? '');
            if (!$parentId || !$typeId) {
                return $this->json(['error' => 'Relation parent/type invalide'], 400);
            }

            $parent = $this->em->getRepository(ParentProfile::class)->find($parentId);
            $type   = $this->em->getRepository(TypeRelation::class)->find($typeId);
            if (!$parent || !$type) {
                return $this->json(['error' => 'Parent ou type de relation introuvable'], 404);
            }

            $relation = new ParentEleveRelation();
            $relation->setParent($parent);
            $relation->setTypeRelation($type);
            $relation->setEleve($eleve);
            $this->em->persist($relation);
            $eleve->addParentEleveRelation($relation);
        }

        // 6. Create Scolarite record
        $scolarite = new Scolarite();
        $scolarite->setEleve($eleve);
        $scolarite->setGroupe($groupe);
        $scolarite->setAnnee($annee);
        $this->em->persist($scolarite);
        $eleve->addScolarite($scolarite);

        // 7. Save everything
        $this->em->persist($eleve);
        $this->em->flush();

        // 8. Return confirmation
        return $this->json([
            'success' => true,
            'message' => sprintf("Élève %s %s ajouté avec succès dans %s (%s)",
                $eleve->getPrenomFr(),
                $eleve->getNomFr(),
                $groupe->getNomFr(),
                $annee->getNom()
            ),
            'eleve_id' => $eleve->getId(),
            'scolarite_id' => $scolarite->getId()
        ], 201);
    }

}
