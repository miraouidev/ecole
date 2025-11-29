<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\MatieresTypeNote;
use App\Entity\NoteEleve;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/scolaire/notes')]
class GradeEntryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/bulk', name: 'grade_entry_bulk', methods: ['POST'])]
    public function bulk(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des données requises
        if (!isset($data['matiere_id'], $data['semestre_id'], $data['type_note_id'], $data['notes'])) {
            return $this->json(['error' => 'Données manquantes (matiere_id, semestre_id, type_note_id, notes)'], 400);
        }

        $matiereId = $data['matiere_id'];
        $semestreId = $data['semestre_id'];
        $typeNoteId = $data['type_note_id'];
        $notesData = $data['notes'];

        // Vérifier la combinaison Matiere/Semestre/TypeNote
        $matieresTypeNote = $this->em->getRepository(MatieresTypeNote::class)->findOneBy([
            'matiere' => $matiereId,
            'semestre' => $semestreId,
            'typeNote' => $typeNoteId,
        ]);

        if (!$matieresTypeNote) {
            return $this->json(['error' => 'Combinaison Matière/Semestre/TypeNote invalide'], 404);
        }

        $processed = 0;
        $errors = [];

        foreach ($notesData as $noteData) {
            if (!isset($noteData['eleve_id'], $noteData['valeur'])) {
                continue;
            }

            $eleveId = $noteData['eleve_id'];
            $valeur = $noteData['valeur'];

            $eleve = $this->em->getRepository(Eleve::class)->find($eleveId);
            if (!$eleve) {
                $errors[] = ['eleve_id' => $eleveId, 'error' => 'Élève introuvable'];
                continue;
            }

            // Chercher si une note existe déjà
            $noteEleve = $this->em->getRepository(NoteEleve::class)->findOneBy([
                'eleve' => $eleve,
                'typeNote' => $matieresTypeNote,
            ]);

            if (!$noteEleve) {
                $noteEleve = new NoteEleve();
                $noteEleve->setEleve($eleve);
                $noteEleve->setTypeNote($matieresTypeNote);
            }

            $noteEleve->setValeur((float)$valeur);

            $this->em->persist($noteEleve);
            $processed++;
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Notes enregistrées avec succès',
            'processed' => $processed,
            'errors' => $errors,
        ]);
    }
}
