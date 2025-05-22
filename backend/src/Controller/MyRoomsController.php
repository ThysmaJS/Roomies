<?php
// src/Controller/MyRoomsController.php
namespace App\Controller;

use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MyRoomsController extends AbstractController
{
    #[Route('/api/my/rooms', name: 'my_rooms', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();
        $rooms = $user->getOwnedRooms(); // doit être mappé côté User
        return $this->json($rooms, 200, [], ['groups' => ['room:read']]);
    }
}
