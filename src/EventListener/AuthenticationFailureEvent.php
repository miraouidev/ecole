<?php

namespace App\EventListener;

use App\Entity\HistoriqueAuth;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthenticationFailureListener
{
    private EntityManagerInterface $em;
    private RequestStack $requestStack;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $data = json_decode($request?->getContent() ?? '{}', true);

        $username = $data['username'] ?? 'unknown';
        $password = $data['password'] ?? '';
        //$passwordPreview = substr($password, 0, 2); // ⚠️ seulement les 2 premières lettres

        $historique = new HistoriqueAuth();
        $historique->setAuthAt(new \DateTimeImmutable());
        $historique->setAuthOk(false);      // ❌ échec
        $historique->setIsConnect(false);
        $historique->setIsRefresh(false);
        $historique->setIp($request?->getClientIp());
        $historique->setNameUser($username);
        //$historique->setExtraInfo("pwd_start=" . $passwordPreview);

        $this->em->persist($historique);
        $this->em->flush();
    }
}
