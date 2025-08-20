<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }        // Current payload
        $payload = $event->getData();
        // Add your custom info
        $payload['info'] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'type' => $user->getType(), // ex: admin, parent, etc.
        ];
        // Override the event payload
        $event->setData($payload);
    }
}
