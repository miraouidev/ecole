<?php

namespace App\EventListener;

use App\Entity\HistoriqueAuth;
use App\Entity\User;
use App\Repository\AnneeScolaireCouranteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    private AnneeScolaireCouranteRepository $anneeRepo;
    private EntityManagerInterface $em;
    private RequestStack $requestStack;

    public function __construct(
        AnneeScolaireCouranteRepository $anneeRepo,
        EntityManagerInterface $em,
        RequestStack $requestStack
    ) {
        $this->anneeRepo    = $anneeRepo;
        $this->em           = $em;
        $this->requestStack = $requestStack;
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        /**
         * @var User $user
         */
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        if (!$user->isActive()) {
            throw new UnauthorizedHttpException('', 'User is not active.');
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
            'annee_scolaire' => [
                'id'        => $annee->getId(),
                'nom'       => $annee->getNom(),
                'dateDebut' => $annee->getDateDebut()?->format('Y-m-d'),
                'dateFin'   => $annee->getDateFin()?->format('Y-m-d'),
            ]
        ];

        $event->setData($data);

        // ✅ Enregistrer dans HistoriqueAuth
        $request = $this->requestStack->getCurrentRequest();

        $historique = new HistoriqueAuth();
        $historique->setUser($user);
        $historique->setAuthAt(new \DateTimeImmutable());
        $historique->setAuthOk(true); // auth réussie
        $historique->setIsConnect(true);
        $historique->setIsRefresh(false);
        $historique->setIp($request?->getClientIp());
        // 🔎 Déterminer le "nameUser"
        if ($user->getAdmininstrateur() !== null) {
            $admin = $user->getAdmininstrateur();
            // en supposant que PersonTrait dans Admininstrateur contient getNom() et getPrenom()
            $fullName = trim($admin->getNomFr() . ' ' . $admin->getPrenomFr());
            $historique->setNameUser($fullName ?: $user->getUsername());
        } else {
            $historique->setNameUser($user->getUsername());
        }

        $this->em->persist($historique);
        $this->em->flush();
    }
}
