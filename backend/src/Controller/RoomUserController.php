<?php

// src/Controller/RoomUserController.php
namespace App\Controller;

use App\Entity\RoomUser;
use App\Repository\RoomRepository;
use App\Repository\RoomUserRepository;
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

        return $this->json([
            'message' => 'Joined room',
            'roomUserId' => $roomUser->getId(),
        ]);
    }

    #[Route('/{id}', name: 'room_user_toggle_ready', methods: ['PATCH'])]
    public function toggleReady(Request $request, RoomUserRepository $repo, EntityManagerInterface $em, int $id): JsonResponse
    {
        $roomUser = $repo->find($id);

        if (!$roomUser) {
            return $this->json(['error' => 'RoomUser not found'], 404);
        }

        if ($roomUser->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Unauthorized'], 403);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['isReady'])) {
            return $this->json(['error' => 'Missing isReady value'], 400);
        }

        $roomUser->setIsReady((bool) $data['isReady']);
        $em->flush();

        return $this->json([
            'message' => 'Status updated',
            'roomUserId' => $roomUser->getId(),
            'isReady' => $roomUser->isReady(),
        ]);
    }
}
