<?php

namespace App\EventListener;

use App\Entity\HistoriqueAuth;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Event\RefreshTokenEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class RefreshTokenListener
{
    private EntityManagerInterface $em;
    private RequestStack $requestStack;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    public function onJwtRefresh(RefreshTokenEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $event->getUser();

        if (!$user) {
            return; // pas de user trouvé
        }

        $historique = new HistoriqueAuth();
        $historique->setUser($user);
        $historique->setAuthAt(new \DateTimeImmutable());
        $historique->setAuthOk(true);
        $historique->setIsConnect(true);
        $historique->setIp($request?->getClientIp());
        $historique->setNameUser($user->getUsername());

        $this->em->persist($historique);
        $this->em->flush();
    }
}
