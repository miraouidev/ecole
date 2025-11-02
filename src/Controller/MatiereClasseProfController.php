<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Groupe;
use App\Entity\MatiereClasseProf;
use App\Entity\Matieres;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/api/scolaire/matiere-classe-prof')]
class MatiereClasseProfController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private CacheInterface $cache) {}

    private function getIdFromIri(string $iri): ?int
    {
        return preg_match('#/(\d+)$#', $iri, $matches) ? (int)$matches[1] : null;
    }

    /**
     * ➕ Ajouter une matière/enseignant à un ou plusieurs groupes
     */
    #[Route('/add', name: 'matiere_classe_prof_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $enseignantId = $this->getIdFromIri($data['enseignant'] ?? '');
        $groupeIris   = $data['groupes'] ?? [];
        $principal    = $data['principal'] ?? false;

        if (!$enseignantId || empty($groupeIris)) {
            return $this->json(['error' => 'enseignant et groupes requis'], 400);
        }

        $enseignant = $this->em->getRepository(Enseignant::class)->find($enseignantId);
        if (!$enseignant) {
            return $this->json(['error' => 'Enseignant introuvable'], 404);
        }

        $matiere = $enseignant->getMatiere();
        if (!$matiere) {
            return $this->json(['error' => 'Cet enseignant n’a pas de matière associée'], 400);
        }

        $errors = [];
        $created = [];

        foreach ($groupeIris as $groupeIri) {
            $groupeId = $this->getIdFromIri($groupeIri);
            if (!$groupeId) {
                $errors[] = ['groupe' => $groupeIri, 'error' => 'Groupe introuvable (IRI invalide)'];
                continue;
            }

            $groupe = $this->em->getRepository(Groupe::class)->find($groupeId);
            if (!$groupe) {
                $errors[] = ['groupe' => $groupeIri, 'error' => 'Groupe introuvable'];
                continue;
            }

            // Vérifier si l’association existe déjà
            $existing = $this->em->getRepository(MatiereClasseProf::class)
                ->findOneBy(['enseignant' => $enseignant, 'matiere' => $matiere, 'groupe' => $groupe]);

            if ($existing) {
                $errors[] = [
                    'groupe' => $groupeIri,
                    'error'  => sprintf(
                        "Association déjà existante : %s - %s - %s",
                        $enseignant->getNomFr(),
                        $matiere->getNomFr(),
                        $groupe->getNomFr()
                    ),
                ];
                continue;
            }

            // Vérifier si un autre enseignant est déjà principal pour cette matière et ce groupe
            $existingPrincipal = $this->em->getRepository(MatiereClasseProf::class)
                ->findOneBy(['matiere' => $matiere, 'groupe' => $groupe, 'principal' => true]);

            if ($existingPrincipal && $principal) {
                $errors[] = [
                    'groupe' => $groupeIri,
                    'error'  => sprintf(
                        "Le professeur %s est déjà principal pour la matière %s dans la classe %s",
                        $existingPrincipal->getEnseignant()->getNomFr(),
                        $matiere->getNomFr(),
                        $groupe->getNomFr()
                    ),
                ];
                continue;
            }

            // Créer la nouvelle association
            $mcp = new MatiereClasseProf();
            $mcp->setEnseignant($enseignant);
            $mcp->setMatiere($matiere);
            $mcp->setGroupe($groupe);
            $mcp->setPrincipal((bool) $principal);

            if ($principal) {
                // Désactiver anciens "principal" du même groupe/matière
                $this->em->createQueryBuilder()
                    ->update(MatiereClasseProf::class, 'm')
                    ->set('m.principal', ':false')
                    ->where('m.groupe = :groupe')
                    ->andWhere('m.matiere = :matiere')
                    ->setParameter('false', false)
                    ->setParameter('groupe', $groupe)
                    ->setParameter('matiere', $matiere)
                    ->getQuery()
                    ->execute();
            }

            $this->em->persist($mcp);
            $created[] = $mcp;
        }

        if (!empty($errors)) {
            return $this->json([
                'message' => 'Certaines associations n’ont pas pu être créées',
                'errors'  => $errors,
            ], 400);
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Classe/Prof ajoutés avec succès',
            'count'   => count($created),
        ]);
    }


    /**
     * ✏️ Mettre à jour un enregistrement (principal true/false)
     */
    #[Route('/update', name: 'matiere_classe_prof_update', methods: ['PATCH'])]
    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $mcpId = $this->getIdFromIri($data['mcp'] ?? '');
        if (!$mcpId) {
            return $this->json(['error' => 'IRI du matiere_classe_prof requis'], 400);
        }

        $mcp = $this->em->getRepository(MatiereClasseProf::class)->find($mcpId);
        if (!$mcp) {
            return $this->json(['error' => 'MatiereClasseProf introuvable'], 404);
        }

        if (isset($data['principal']) && $data['principal'] === true) {
            // Mettre les autres à false
            $this->em->createQueryBuilder()
                ->update(MatiereClasseProf::class, 'm')
                ->set('m.principal', ':false')
                ->where('m.groupe = :groupe')
                ->andWhere('m.matiere = :matiere')
                ->andWhere('m != :current')
                ->setParameter('false', false)
                ->setParameter('groupe', $mcp->getGroupe())
                ->setParameter('matiere', $mcp->getMatiere())
                ->setParameter('current', $mcp)
                ->getQuery()
                ->execute();
        }
        $mcp->setPrincipal((bool)$data['principal']);

        $this->em->flush();

        return $this->json(['message' => 'MatiereClasseProf mis à jour']);
    }

    /**
     * 🔄 Remplacer un enseignant (oldProf) par un autre (newProf)
     */
    #[Route('/replace-prof', name: 'matiere_classe_prof_replace_prof', methods: ['PATCH'])]
    public function replaceProf(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $oldProfId  = $this->getIdFromIri($data['oldProf'] ?? '');
        $newProfId  = $this->getIdFromIri($data['newProf'] ?? '');
        $groupeIris = $data['groupes'] ?? [];
        $all        = (bool)($data['all'] ?? false);

        if (!$oldProfId || !$newProfId) {
            return $this->json(['error' => 'oldProf et newProf requis'], 400);
        }        
        if ($oldProfId === $newProfId) {
            return $this->json([
                'error' => 'Le même professeur ne peut pas être remplacé par lui-même.'
            ], 400);
        }

        $oldProf = $this->em->getRepository(Enseignant::class)->find($oldProfId);
        $newProf = $this->em->getRepository(Enseignant::class)->find($newProfId);

        $errors = [];
        if (!$oldProf) {
            $errors[] = ['entity' => 'oldProf', 'id' => $oldProfId, 'error' => 'Professeur introuvable'];
        }
        if (!$newProf) {
            $errors[] = ['entity' => 'newProf', 'id' => $newProfId, 'error' => 'Professeur introuvable'];
        }
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }

        // 🔍 Vérifier que les deux professeurs ont la même matière
        $oldMatiere = $oldProf->getMatiere();
        $newMatiere = $newProf->getMatiere();

        if (!$oldMatiere || !$newMatiere) {
            return $this->json(['error' => 'Les deux enseignants doivent avoir une matière associée'], 400);
        }

        if ($oldMatiere->getId() !== $newMatiere->getId()) {
            return $this->json([
                'error' => sprintf(
                    "Les matières diffèrent : %s (%d) ≠ %s (%d)",
                    $oldMatiere->getNomFr(),
                    $oldMatiere->getId(),
                    $newMatiere->getNomFr(),
                    $newMatiere->getId()
                )
            ], 400);
        }

        $matiere = $oldMatiere;
        $updated = 0;
        $errors = [];

        if ($all) {
            $mcps = $this->em->getRepository(MatiereClasseProf::class)
                ->findBy(['enseignant' => $oldProf, 'matiere' => $matiere]);

            if (empty($mcps)) {
                $errors[] = ['entity' => 'MatiereClasseProf', 'id' => $oldProfId, 'error' => 'Aucune association trouvée'];
            }

            foreach ($mcps as $mcp) {
                $mcp->setEnseignant($newProf);
                $updated++;
            }
        } else {
            if (empty($groupeIris)) {
                return $this->json(['error' => 'groupes requis si all=false'], 400);
            }

            foreach ($groupeIris as $groupeIri) {
                $groupeId = $this->getIdFromIri($groupeIri);
                if (!$groupeId) {
                    $errors[] = ['entity' => 'groupe', 'iri' => $groupeIri, 'error' => 'IRI invalide'];
                    continue;
                }

                $groupe = $this->em->getRepository(Groupe::class)->find($groupeId);
                if (!$groupe) {
                    $errors[] = ['entity' => 'groupe', 'id' => $groupeId, 'error' => 'Groupe introuvable'];
                    continue;
                }

                $mcp = $this->em->getRepository(MatiereClasseProf::class)->findOneBy([
                    'enseignant' => $oldProf,
                    'matiere'    => $matiere,
                    'groupe'     => $groupe,
                ]);

                if ($mcp) {
                    $mcp->setEnseignant($newProf);
                    $updated++;
                } else {
                    $errors[] = [
                        'entity' => 'MatiereClasseProf',
                        'groupe' => $groupeId,
                        'error'  => 'Association non trouvée pour ce groupe',
                    ];
                }
            }
        }

        if (!empty($errors)) {
            return $this->json([
                'message' => 'Des erreurs sont survenues',
                'updated' => $updated,
                'errors'  => $errors,
            ], 400);
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Remplacement effectué avec succès',
            'count'   => $updated,
        ]);
    }


    /**
     * 🌟 Définir un prof comme principal (ou non)
     */
    #[Route('/set-principal', name: 'matiere_classe_prof_set_principal', methods: ['PATCH'])]
    public function setPrincipal(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $profId = $this->getIdFromIri($data['prof'] ?? '');
        $principal = $data['principal'] ?? null;

        if (!$profId || !is_bool($principal)) {
            return $this->json(['error' => 'Paramètres requis: prof (IRI) et principal (bool)'], 400);
        }

        $prof = $this->em->getRepository(Enseignant::class)->find($profId);
        if (!$prof) {
            return $this->json(['error' => 'Professeur introuvable'], 404);
        }

        // Tous les MCP liés à ce prof
        $mcps = $this->em->getRepository(MatiereClasseProf::class)->findBy(['enseignant' => $prof]);

        if (empty($mcps)) {
            return $this->json(['error' => 'Aucun enregistrement trouvé pour ce prof'], 404);
        }

        foreach ($mcps as $mcp) {
            if ($principal === true) {
                // Mettre les autres profs du même groupe+matière à false
                $this->em->createQueryBuilder()
                    ->update(MatiereClasseProf::class, 'm')
                    ->set('m.principal', ':false')
                    ->where('m.groupe = :groupe')
                    ->andWhere('m.matiere = :matiere')
                    ->setParameter('false', false)
                    ->setParameter('groupe', $mcp->getGroupe())
                    ->setParameter('matiere', $mcp->getMatiere())
                    ->getQuery()
                    ->execute();

                // Mettre ce prof en principal
                $mcp->setPrincipal(true);

            } else {
                // Seulement ce prof passe en false
                $mcp->setPrincipal(false);
            }
        }

        $this->em->flush();

        return $this->json([
            'message' => $principal ? 'Prof défini comme principal' : 'Prof retiré de principal',
        ]);
    }


    #[Route('set-prof',name: 'matiere_classe_prof_set_principal',methods: ['PATCH'])]
    public function setProf(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $profId    = $this->getIdFromIri($data['prof'] ?? '');
        $principal = (bool)($data['principal'] ?? false);
        $mcpIris   = $data['matiereClasseProf'] ?? [];

        $errors  = [];
        $updated = [];

        if (!$profId) {
            return $this->json(['error' => 'prof requis'], 400);
        }

        $prof = $this->em->getRepository(Enseignant::class)->find($profId);
        if (!$prof) {
            return $this->json(['error' => 'Professeur introuvable'], 404);
        }

        foreach ($mcpIris as $mcpIri) {
            $mcpId = $this->getIdFromIri($mcpIri);
            if (!$mcpId) {
                $errors[] = ['entity' => 'MatiereClasseProf', 'iri' => $mcpIri, 'error' => 'IRI invalide'];
                continue;
            }

            $mcp = $this->em->getRepository(MatiereClasseProf::class)->find($mcpId);
            if (!$mcp) {
                $errors[] = ['entity' => 'MatiereClasseProf', 'id' => $mcpId, 'error' => 'Introuvable'];
                continue;
            }

            if ($mcp->getEnseignant() !== null) {
                $errors[] = [
                    'entity' => 'MatiereClasseProf',
                    'id'     => $mcpId,
                    'error'  => sprintf(
                        "Déjà affecté à %s",
                        $mcp->getEnseignant()->getNomFr() ?? 'Enseignant inconnu'
                    ),
                ];
                continue;
            }

            // Assigner le prof car MCP est vide
            $mcp->setEnseignant($prof);
            $mcp->setPrincipal($principal);
            $updated[] = $mcpId;
        }

        if (!empty($errors)) {
            return $this->json([
                'message' => 'Des erreurs sont survenues',
                'updated' => $updated,
                'errors'  => $errors,
            ], 400);
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Mise à jour effectuée',
            'count'   => count($updated),
            'updated' => $updated,
        ]);
    }

    /// api get 
    /**
     * 📌 Récupérer les infos d’un prof : groupes + matières
     */
    #[Route('/by-prof/{id}', name: 'matiere_classe_prof_by_prof', methods: ['GET'])]
    public function getByProf(int $id): JsonResponse
    {
        $cacheKey = "matiere_prof_$id";

        $result = $this->cache->get($cacheKey, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(3600); // ⏱️ cache 1h

            $prof = $this->em->getRepository(Enseignant::class)->find($id);
            if (!$prof) {
                return null;
            }

            $mcps = $this->em->getRepository(MatiereClasseProf::class)->findBy(['enseignant' => $prof]);

            $groupes = [];
            $matieres = [];

            foreach ($mcps as $mcp) {
                $groupe = $mcp->getGroupe();
                $matiere = $mcp->getMatiere();

                if ($groupe) {
                    $groupes[$groupe->getId()] = [
                        'id'   => $groupe->getId(),
                        'nom_ar'  => $groupe->getNomAr() ?? "Groupe {$groupe->getId()}",
                        'nom_fr'  => $groupe->getNomFr() ?? "Groupe {$groupe->getId()}",

                    ];
                }

                if ($matiere) {
                    $matieres[$matiere->getId()] = [
                        'id'   => $matiere->getId(),
                        'nom_ar'  => $matiere->getNomAr() ?? "Groupe {$groupe->getId()}",
                        'nom_fr'  => $matiere->getNomFr() ?? "Groupe {$groupe->getId()}",
                    ];
                }
            }

            return [
                'prof' => [
                    'id'       => $prof->getId(),
                    'nom'      => $prof->getNomFr(),
                    'prenom'   => $prof->getPrenomFr(),
                    'cin'      => $prof->getCin(),
                    'mobile'   => $prof->getMobile(),
                ],
                'groupes'  => array_values($groupes),
                'matieres' => array_values($matieres),
            ];
        });

        if ($result === null) {
            return $this->json(['error' => 'Prof introuvable'], 404);
        }

        return $this->json($result);
    }

}
