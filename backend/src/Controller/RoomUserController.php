<?php

namespace App\Controller;

use App\Entity\Room;
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
    #[Route('/room_users', name: 'room_user_join', methods: ['POST'])]
    public function join(Request $request, RoomRepository $roomRepo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['room'])) {
            return $this->json(['error' => 'Missing room'], 400);
        }

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

    #[Route('/room_users/{id}/ready', name: 'room_user_toggle_ready', methods: ['PATCH'])]
    public function toggleReady(Request $request, RoomUserRepository $repo, EntityManagerInterface $em, int $id): JsonResponse
    {
        $roomUser = $repo->find($id);
        if (!$roomUser) return $this->json(['error' => 'RoomUser not found'], 404);
        if ($roomUser->getUser() !== $this->getUser()) return $this->json(['error' => 'Unauthorized'], 403);

        $data = json_decode($request->getContent(), true);
        if (!isset($data['isReady'])) return $this->json(['error' => 'Missing isReady'], 400);

        $roomUser->setIsReady((bool)$data['isReady']);

        $room = $roomUser->getRoom();
        $allReady = true;
        if (count($room->getRoomUsers()) >= 2) {
            foreach ($room->getRoomUsers() as $ru) {
                if (!$ru->isReady()) {
                    $allReady = false;
                    break;
                }
            }
        } else {
            $allReady = false;
        }

        if ($allReady && !$room->getCurrentGame()) {
            $room->setCurrentGame('morpion');
        }

        $em->flush();
        return $this->json(['message' => 'Updated', 'isReady' => $roomUser->isReady()]);
    }

        #[Route('/my/joined', name: 'joined_rooms', methods: ['GET'])]
public function joinedRooms(EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();

    // On récupère toutes les RoomUser où $user participe
    $qb = $em->createQueryBuilder();
    $qb->select('r')
        ->from(Room::class, 'r')
        ->join('r.roomUsers', 'ru')
        ->where('ru.user = :user')
        ->setParameter('user', $user);

    // On ne veut PAS les rooms dont il est owner (pour la séparation)
    $qb->andWhere('r.owner != :userOwner')->setParameter('userOwner', $user);

    $rooms = $qb->getQuery()->getResult();

    return $this->json($rooms, 200, [], ['groups' => 'room:read']);
}
}
