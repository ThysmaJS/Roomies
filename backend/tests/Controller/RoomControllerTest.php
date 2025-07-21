<?php
// tests/Controller/RoomControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RoomControllerTest extends WebTestCase
{
    private function createAuthenticatedClient(): object
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        // Créer ou récupérer un user test existant
        $userRepo = $em->getRepository(User::class);
        $user = $userRepo->findOneBy(['username' => 'demo_user']);

        if (!$user) {
            $user = new User();
            $user->setEmail('demo@example.com');
            $user->setUsername('demo_user');

            // Utilise le PasswordHasher Symfony (meilleure pratique !)
            $hasher = self::getContainer()->get(UserPasswordHasherInterface::class);
            $hashedPassword = $hasher->hashPassword($user, 'testpass');
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();
        }

        // Génère le JWT pour ce user
        $jwtManager = self::getContainer()->get(JWTTokenManagerInterface::class);
        $token = $jwtManager->create($user);

        // Ajoute le header Authorization
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));
        return $client;
    }

    public function testListRooms(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/rooms');

        $this->assertResponseIsSuccessful(); // status 200
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
    }
}
