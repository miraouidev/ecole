<?php
namespace App\Controller;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RefreshTokenController
{
    #[Route('/auth/token/refresh2', name: 'app_token_refresh', methods: ['POST'])]
    public function refresh(
        Request $request,
        JWTTokenManagerInterface $jwtManager,
        UserProviderInterface $userProvider,
        RefreshTokenManagerInterface $refreshTokenManager
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

        return new JsonResponse([
            'token' => $jwt,
            'refresh_token' => $newRefresh->getRefreshToken(),
        ]);
    }
}
