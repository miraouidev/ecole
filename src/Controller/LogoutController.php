<?php
namespace App\Controller;

use App\Entity\HistoriqueAuth;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class LogoutController extends AbstractController
{
    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function __invoke(
        Request $request,
        Security $security,
        EntityManagerInterface $em
    ): JsonResponse {
        /** @var User $user */
        $user = $security->getUser();
        if (!$user instanceof UserInterface) {
            throw new UnauthorizedHttpException('', 'Invalid or missing token.');
        }

        // write history
        $h = new HistoriqueAuth();
        $h->setUser($user);
        $h->setAuthAt(new \DateTimeImmutable());
        $h->setAuthOk(true);        // the operation itself succeeded
        $h->setIsConnect(false);    // logout
        $h->setIsRefresh(false);
        $h->setIp($request->getClientIp());

        // copy the same "nameUser" logic you use on login
        if ($user->getAdmininstrateur() !== null) {
            $admin = $user->getAdmininstrateur();
            $fullName = trim(($admin->getNomFr() ?? '').' '.($admin->getPrenomFr() ?? ''));
            $h->setNameUser($fullName ?: $user->getUserIdentifier());
        } else {
            $h->setNameUser($user->getUserIdentifier());
        }

        $em->persist($h);
        $em->flush();

        // if you only use short-lived access tokens, client just deletes them.
        return $this->json(['message' => 'Logged out.'], 200);
    }
}
