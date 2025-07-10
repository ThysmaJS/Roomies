<?php

// src/Controller/RoomUserController.php
namespace App\Controller;

use App\Entity\RoomUser;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/room_users')]
class RoomUserController extends AbstractController
{
    #[Route('', name: 'room_user_join', methods: ['POST'])]
    public function join(Request $request, RoomRepository $roomRepo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['room'])) {
            return $this->json(['error' => 'Missing room'], 400);
        }

        // Ex: /api/rooms/3 → extract 3
        preg_match('#/api/rooms/(\d+)#', $data['room'], $matches);
        $roomId = $matches[1] ?? null;

        if (!$roomId) {
            return $this->json(['error' => 'Invalid room format'], 400);
        }

        $room = $roomRepo->find($roomId);
        if (!$room) {
            return $this->json(['error' => 'Room not found'], 404);
        }

        $roomUser = new RoomUser();
        $roomUser->setRoom($room);
        $roomUser->setUser($this->getUser());

        $em->persist($roomUser);
        $em->flush();

        return $this->json(['message' => 'Joined room']);
    }
}
