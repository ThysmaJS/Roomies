<?php
// src/Controller/LoginController.php
namespace App\Controller;

use App\Dto\LoginUserInput;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[AsController]
class LoginController extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload] LoginUserInput $data,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $user = $em->getRepository(User::class)->findOneBy(['email' => $data->email]);

        if (!$user || !$hasher->isPasswordValid($user, $data->password)) {
            return new JsonResponse(['error' => 'Email ou mot de passe invalide.'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }
}
