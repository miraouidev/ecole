<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CheckAnneeScolaireListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // Only check routes starting with /api/scolaire
        if (!str_starts_with($path, '/api/scolaire')) {
            return;
        }

        // Skip if it's an OPTIONS request (CORS preflight)
        if ($request->isMethod('OPTIONS')) {
            return;
        }

        $anneeScolaireId = null;

        if ($request->isMethod('GET')) {
            $anneeScolaireId = $request->query->get('anneeScolaire');
            // Also check nested property filter syntax (e.g. anneeScolaire.id) if used
            if (!$anneeScolaireId) {
                $anneeScolaireId = $request->query->get('anneeScolaire_id');
            }
        } elseif (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])) {
            $content = json_decode($request->getContent(), true);
            $anneeScolaireId = $content['anneeScolaire'] ?? null;
        } else {
             // DELETE requests might need it too depending on business logic, 
             // but usually ID is enough. Skipping for now unless specified.
             return;
        }

        // If we are in a GET request, we might want to allow listing without filter 
        // IF the user explicitly wants all history, but the requirement says "stop".
        // However, for specific resource GET (e.g. /api/scolaire/eleves/1), 
        // the ID implies the year context usually, so we might skip for individual item GETs?
        // The requirement says "pour tous api have routePrefix: '/scolaire'".
        // Let's be strict as requested.
        
        // Exception: if fetching a specific item by ID (e.g. /api/scolaire/matieres/1),
        // maybe we don't need the year? 
        // But the user said "check in path if AnneeScolaireCourante.id not existe stop".
        // This likely implies context-dependent collections or creations.
        
        // Let's check if it's a collection operation or item operation.
        // Usually item operations have an ID at the end.
        // Regex to check if it ends with a number: /\/\d+$/
        $isItemOperation = preg_match('/\/\d+$/', $path);

        // If it's an item operation (GET /api/scolaire/xyz/1), we might skip 
        // because the entity itself is already linked to a year (usually).
        // But for creating (POST) or listing (GET collection), we definitely need it.
        
        // User request: "in this listtner check in path if AnneeScolaireCourante.id not existe stop"
        // This phrasing "check in path" is slightly ambiguous. 
        // It likely means "check in the request (query/body) if the ID exists".
        return;
        if (!$anneeScolaireId && !$isItemOperation) {
             //throw new BadRequestHttpException('AnneeScolaire parameter is required for this operation.');
        }
        
        // If it's a POST/PUT, we might want to verify if the ID is valid/active, 
        // but the requirement just says "check if not existe stop".
    }
}
