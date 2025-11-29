<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminResetPasswordController extends AbstractController
{
    #[Route('/api/admin/users/{id}/reset-password', name: 'admin_reset_password', methods: ['POST'])]
    public function __invoke(
        User $user,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // only admins

        $data = json_decode($request->getContent(), true);
        $newPassword = $data['newPassword'] ?? null;

        if (!$newPassword) {
            return $this->json(['error' => 'Missing newPassword'], 400);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
        $em->flush();

        return $this->json(['message' => 'Password reset successfully']);
    }

     #[Route('/api/admin/users/{id}/update-roles', name: 'admin_update_roles', methods: ['POST'])]
    public function updateRoles(
        User $user,
        Request $request,
        EntityManagerInterface $em,
        \App\Repository\RoleAppRepository $roleAppRepository
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // only admins can do this

        $data = json_decode($request->getContent(), true);
        $roles = $data['roles'] ?? null;

        if (!$roles || !is_array($roles)) {
            return $this->json(['error' => 'Missing or invalid roles array'], 400);
        }

        // Restrict allowed roles (security!) - Fetch from database
        $roleAppEntities = $roleAppRepository->findAll();
        $allowedRoles = array_map(fn($roleApp) => $roleApp->getCode(), $roleAppEntities);

        $filteredRoles = array_values(array_intersect($roles, $allowedRoles));

        // Always ensure user has at least ROLE_USER
        if (!in_array('ROLE_USER', $filteredRoles, true)) {
            $filteredRoles[] = 'ROLE_USER';
        }

        $user->setRoles($filteredRoles);
        $em->flush();

        return $this->json([
            'message' => 'Roles updated successfully',
            'roles' => $user->getRoles()
        ]);
    }
}
