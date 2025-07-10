<?php 

// src/Controller/RoomController.php
namespace App\Controller;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rooms')]
class RoomController extends AbstractController
{
    #[Route('', name: 'room_list', methods: ['GET'])]
    public function list(RoomRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll(), 200, [], ['groups' => 'room:read']);
    }

    #[Route('', name: 'room_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $room = new Room();
        $room->setName($data['name'] ?? '');
        $room->setMaxPlayers($data['maxPlayers'] ?? 2);
        $room->setOwner($this->getUser());

        $em->persist($room);
        $em->flush();

        return $this->json($room, 201, [], ['groups' => 'room:read']);
    }

    #[Route('/{id}', name: 'room_delete', methods: ['DELETE'])]
    public function delete(Room $room, EntityManagerInterface $em): JsonResponse
    {
        if ($room->getOwner() !== $this->getUser()) {
            return $this->json(['error' => 'Unauthorized'], 403);
        }

        $em->remove($room);
        $em->flush();

        return $this->json(null, 204);
    }

    #[Route('/my/rooms', name: 'my_rooms', methods: ['GET'])]
    public function myRooms(RoomRepository $repo): JsonResponse
    {
        $rooms = $repo->findByOwner($this->getUser());
        return $this->json($rooms, 200, [], ['groups' => 'room:read']);
    }
}
