<?php

namespace App\EventListener;

use App\Repository\AnneeScolaireCouranteRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    private AnneeScolaireCouranteRepository $anneeRepo;

    public function __construct(AnneeScolaireCouranteRepository $anneeRepo)
    {
        $this->anneeRepo = $anneeRepo;
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        if (!$user->isActive()) {
            throw new UnauthorizedHttpException('User is not active.');
        }

        $data = $event->getData();

        // 🔎 Chercher l'année scolaire active
        $annee = $this->anneeRepo->findOneBy(['isActive' => true]);
        if (!$annee) {
            throw new UnauthorizedHttpException('', 'Aucune année scolaire active trouvée.');
        }
        $data['info'] = [
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'type'  => $user->getType(),
            'annee_scolaire' => $annee ? [
                'id'        => $annee->getId(),
                'nom'       => $annee->getNom(),
                'dateDebut' => $annee->getDateDebut()?->format('Y-m-d'),
                'dateFin'   => $annee->getDateFin()?->format('Y-m-d'),
            ] : null
        ];

        $event->setData($data);
    }
}
