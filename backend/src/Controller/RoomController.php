<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\RoomUser;
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
        $rooms = $repo->findAllWithUsers(); // 🔥 nouvelle méthode avec jointure
        return $this->json($rooms, 200, [], ['groups' => 'room:read']);
    }

    #[Route('', name: 'room_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $room = new Room();
        $room->setName($data['name'] ?? '');
        $room->setMaxPlayers($data['maxPlayers'] ?? 2);
        $room->setOwner($user);

        // ➕ Ajout de l'utilisateur comme participant (RoomUser)
        $roomUser = new RoomUser();
        $roomUser->setRoom($room);
        $roomUser->setUser($user);
        $roomUser->setIsReady(false); // par défaut
        $room->addRoomUser($roomUser);

        $em->persist($room);
        $em->persist($roomUser); // facultatif si cascade
        $em->flush();

        return $this->json($room, 201, [], ['groups' => 'room:read']);
    }

    #[Route('/my/rooms', name: 'my_rooms', methods: ['GET'])]
    public function myRooms(RoomRepository $repo): JsonResponse
    {
        $rooms = $repo->findByOwner($this->getUser());
        return $this->json($rooms, 200, [], ['groups' => 'room:read']);
    }

    #[Route('/{id}', name: 'room_get', methods: ['GET'])]
    public function get(Room $room): JsonResponse
    {
        return $this->json($room, 200, [], ['groups' => 'room:read']);
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

#[Route('/{id}', name: 'room_update', methods: ['PATCH'])]
public function update(Request $request, Room $room, EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();
    if ($room->getOwner() !== $user) {
        return $this->json(['error' => 'Unauthorized'], 403);
    }

    $data = json_decode($request->getContent(), true);
    if (isset($data['name'])) {
        $room->setName($data['name']);
    }
    if (isset($data['maxPlayers'])) {
        $room->setMaxPlayers($data['maxPlayers']);
    }

    $em->flush();

    return $this->json($room, 200, [], ['groups' => 'room:read']);
}


}
