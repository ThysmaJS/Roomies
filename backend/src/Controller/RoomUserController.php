<?php

namespace App\Controller;

use App\Entity\RoomUser;
use App\Repository\RoomRepository;
use App\Repository\RoomUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class RoomUserController extends AbstractController
{
    // 1. Rejoindre une room avec champ `room` dans le body
    #[Route('/room_users', name: 'room_user_join', methods: ['POST'])]
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

        $user = $this->getUser();
        /** @var \App\Entity\User $user */

        foreach ($room->getRoomUsers() as $ru) {
            if ($ru->getUser()?->getId() === $user->getId()) {
                return $this->json(['error' => 'Vous avez déjà rejoint cette room.'], 400);
            }
        }

        $roomUser = new RoomUser();
        $roomUser->setRoom($room);
        $roomUser->setUser($user);
        $roomUser->setIsReady(false);

        $em->persist($roomUser);
        $em->flush();

        return $this->json([
            'message' => 'Joined room',
            'roomUserId' => $roomUser->getId(),
        ]);
    }

    // 2. Rejoindre une room via son id directement (ex: POST /api/rooms/5/join)
    #[Route('/rooms/{id}/join', name: 'api_room_join', methods: ['POST'])]
    public function joinRoomById(
        int $id,
        RoomRepository $roomRepo,
        RoomUserRepository $roomUserRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $room = $roomRepo->find($id);
        if (!$room) {
            return $this->json(['error' => 'Room not found'], 404);
        }

        $user = $this->getUser();
        /** @var \App\Entity\User $user */

        $existing = $roomUserRepo->findOneBy([
            'room' => $room,
            'user' => $user
        ]);

        if ($existing) {
            return $this->json(['error' => 'Vous avez déjà rejoint cette room.'], 400);
        }

        $roomUser = new RoomUser();
        $roomUser->setRoom($room);
        $roomUser->setUser($user);
        $roomUser->setIsReady(false);

        $em->persist($roomUser);
        $em->flush();

        return $this->json([
            'message' => 'Rejoint avec succès',
            'roomUserId' => $roomUser->getId(),
        ]);
    }

    // 3. Modifier l’état "prêt" d’un utilisateur dans une room
    #[Route('/room_users/{id}', name: 'room_user_toggle_ready', methods: ['PATCH'])]
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
