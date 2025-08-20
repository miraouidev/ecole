<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
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

        $data['info'] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'type' => $user->getType(),
        ];

        $event->setData($data);
    }
}
