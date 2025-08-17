<?php

namespace App\EventSubscriber;

use App\Entity\LogApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RequestStack;

class ApiLogSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private RequestStack $requestStack
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // On ne log QUE les appels /api/*
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        //dd('Logging API request: ' . $request->getPathInfo());
        $log = new LogApi();
        $log->setDateAt(new \DateTimeImmutable());
        $log->setMethod($request->getMethod());
        $log->setIp($request->getClientIp());
        $log->setDevice($request->headers->get('User-Agent'));

        // User
        $user = $this->security->getUser();
        if ($user) {
            $log->setUser($user);
            $log->setUtilisateur($user->getUserIdentifier());
        }

        // Contenu (JSON ou query params)
        $content = [];
        if ($request->getContent()) {
            $content = json_decode($request->getContent(), true);
        }
        if (empty($content)) {
            $content = $request->query->all();
        }
        $log->setContenu($content);

        $this->em->persist($log);
        $this->em->flush();
    }
}
