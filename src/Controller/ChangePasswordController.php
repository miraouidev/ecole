<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    #[Route('/api/users/change-password', name: 'user_change_password', methods: ['POST'])]
    public function __invoke(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $oldPassword = $data['oldPassword'] ?? null;
        $newPassword = $data['newPassword'] ?? null;

        if (!$oldPassword || !$newPassword) {
            return $this->json(['error' => 'Missing oldPassword or newPassword'], 400);
        }

        if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
            return $this->json(['error' => 'Old password is incorrect'], 400);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
        $em->flush();

        return $this->json(['message' => 'Password changed successfully']);
    }
}
