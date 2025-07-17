<?php
// tests/Controller/RoomControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class RoomControllerTest extends WebTestCase
{
    private function createAuthenticatedClient(): object
    {
        $client = static::createClient();
        $container = static::getContainer();

        // Créer ou récupérer un user test existant
        $userRepo = $container->get('doctrine')->getRepository(User::class);
        $user = $userRepo->findOneBy(['username' => 'testuser']);
        if (!$user) {
            // Crée un user si besoin (simplifié : adapte selon ton projet)
            $user = new User();
            $user->setEmail('test@example.com');
            $user->setUsername('testuser');
            $user->setPassword(password_hash('testpass', PASSWORD_BCRYPT));
            $em = $container->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush();
        }

        // Génère le JWT pour ce user
        $jwtManager = $container->get(JWTTokenManagerInterface::class);
        $token = $jwtManager->create($user);

        // Ajoute le header Authorization
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));
        return $client;
    }

    public function testListRooms()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/rooms');

        $this->assertResponseIsSuccessful(); // status 200
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
    }
}
