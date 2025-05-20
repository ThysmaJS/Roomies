<?php

namespace App\Controller;

use App\Dto\RegisterUserInput;
use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class RegisterController
{
    public function __invoke(
        #[MapRequestPayload] RegisterUserInput $data,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        RoleRepository $roleRepository
    ): JsonResponse {
        $user = new User();
        $user->setUsername($data->username);
        $user->setEmail($data->email);
        $user->setCreatedAt(new \DateTime());
        $user->setScore(0);

        $role = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
        if ($role) {
            $user->setRole($role);
        }

        $hashedPassword = $hasher->hashPassword($user, $data->password);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'User registered successfully'], 201);
    }
}
