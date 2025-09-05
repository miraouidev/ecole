<?php

namespace App\Controller;

use App\Entity\HistoriqueAuth;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RefreshTokenController
{
    #[Route('/auth/token/refresh-jwt', name: 'app_token_refresh', methods: ['POST'])]
    public function refresh(
        Request $request,
        JWTTokenManagerInterface $jwtManager,
        UserProviderInterface $userProvider,
        RefreshTokenManagerInterface $refreshTokenManager,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['refresh_token'])) {
            return new JsonResponse(['error' => 'missing_refresh_token'], 400);
        }

        $oldToken = $refreshTokenManager->get($data['refresh_token']);
        if (!$oldToken || !$oldToken->isValid()) {
            return new JsonResponse(['error' => 'invalid_refresh_token'], 401);
        }

        // Charger l’utilisateur
        /** @var UserInterface $user */
        $user = $userProvider->loadUserByIdentifier($oldToken->getUsername());

        // Créer un nouveau access token
        $jwt = $jwtManager->create($user);

        // Supprimer l’ancien refresh
        $refreshTokenManager->delete($oldToken);

        // Créer un nouveau refresh
        $newRefresh = $refreshTokenManager->create();
        $newRefresh->setRefreshToken();
        $newRefresh->setUsername($user->getUserIdentifier());
        $newRefresh->setValid((new \DateTime())->modify('+12 hours'));

        $refreshTokenManager->save($newRefresh);

        // ✅ Enregistrer un HistoriqueAuth
        $historique = new HistoriqueAuth();
        $historique->setUser($user);
        $historique->setAuthAt(new \DateTimeImmutable());
        $historique->setAuthOk(true);
        $historique->setIsConnect(true);
        $historique->setIsRefresh(true);
        $historique->setIp($request?->getClientIp());
        $historique->setNameUser($user->getUserIdentifier());

        $em->persist($historique);
        $em->flush();

        return new JsonResponse([
            'token' => $jwt,
            'refresh_token' => $newRefresh->getRefreshToken(),
        ]);
    }
}
