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
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

#[AsController]
class RegisterController
{
    public function __invoke(
        #[MapRequestPayload] RegisterUserInput $data,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        RoleRepository $roleRepository,
        RateLimiterFactory $registerApiLimiter,
        ValidatorInterface $validator
    ): JsonResponse {
        // 🔒 Anti-spam (rate limit par email ou IP)
        $limiter = $registerApiLimiter->create($data->email ?? 'anonymous');
        if (!$limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException('Trop de tentatives, réessaie plus tard.');
        }

        // 🔎 Vérifie si l’email existe déjà
        $existing = $em->getRepository(User::class)->findOneBy(['email' => $data->email]);
        if ($existing) {
            return new JsonResponse(['error' => 'Cet email est déjà utilisé.'], Response::HTTP_CONFLICT);
        }

        // 👤 Création du user
        $user = new User();
        $user->setUsername($data->username);
        $user->setEmail($data->email);
        $user->setCreatedAt(new \DateTime());
        $user->setScore(0);

        // 🛡️ Ajout du rôle
        $role = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
        if ($role) {
            $user->setRole($role);
        }

        // 🔐 Hash du mot de passe
        $hashedPassword = $hasher->hashPassword($user, $data->password);
        $user->setPassword($hashedPassword);

        // ✅ Validation des contraintes (facultatif si déjà validé en amont)
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        // 💾 Enregistrement en BDD
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'Inscription réussie.'], Response::HTTP_CREATED);
    }
}
