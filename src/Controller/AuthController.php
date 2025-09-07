<?php

namespace App\Controller;

use App\Entity\RefreshToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/auth/token/refresh-jwt', name: 'api_token_refresh', methods: ['POST'])]
    public function refreshToken(
        Request $request,
        EntityManagerInterface $em,
        ManagerRegistry $doctrine,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $refreshToken = $data['refresh_token'] ?? null;
        if (!$refreshToken) {
            return new JsonResponse(['error' => 'Missing refresh token'], 400);
        }

        // find refresh token in DB
        $storedToken = $em->getRepository(RefreshToken::class)
            ->findOneBy(['refreshToken' => $refreshToken]);

        if (!$storedToken || !$storedToken->isValid()) {
            return new JsonResponse(['error' => 'Invalid or expired refresh token'], 401);
        }

        // get user by username
        $user = $doctrine->getRepository(User::class)
            ->findOneBy(['username' => $storedToken->getUsername()]);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        // issue new JWT
        $newJwt = $jwtManager->create($user);

        // (optional) single-use refresh token = delete it after usage
        $em->remove($storedToken);
        $em->flush();

        return new JsonResponse([
            'token' => $newJwt,
            'refresh_token' => $refreshToken, // or generate new one if you prefer
        ]);
    }
}
