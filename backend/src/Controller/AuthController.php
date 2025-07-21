<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthController extends AbstractController
{
#[Route('/api/register', name: 'api_register', methods: ['POST'])]
public function register(
    Request $request,
    EntityManagerInterface $em,
    UserPasswordHasherInterface $passwordHasher
): JsonResponse {
    $data = json_decode($request->getContent(), true);

    if (!isset($data['email'], $data['username'], $data['password'])) {
        return new JsonResponse(['detail' => 'Champs requis manquants.'], Response::HTTP_BAD_REQUEST);
    }

    $userRepo = $em->getRepository(User::class);

    // Vérifie unicité username
    if ($userRepo->findOneBy(['username' => $data['username']])) {
        return new JsonResponse(['detail' => 'Ce nom d\'utilisateur est déjà pris.'], Response::HTTP_CONFLICT);
    }

    // Vérifie unicité email
    if ($userRepo->findOneBy(['email' => $data['email']])) {
        return new JsonResponse(['detail' => 'Cet email est déjà utilisé.'], Response::HTTP_CONFLICT);
    }

    $user = new User();
    $user->setEmail($data['email']);
    $user->setUsername($data['username']);
    $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
    $user->setPassword($hashedPassword);

    $em->persist($user);
    $em->flush();

    return new JsonResponse(['message' => 'Utilisateur créé avec succès'], Response::HTTP_CREATED);
}
}