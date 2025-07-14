<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function findAllWithUsers(): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.roomUsers', 'ru')
            ->addSelect('ru')
            ->leftJoin('ru.user', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult();
    }

    public function findByOwner($owner): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.owner = :owner')
            ->setParameter('owner', $owner)
            ->leftJoin('r.roomUsers', 'ru')
            ->addSelect('ru')
            ->leftJoin('ru.user', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult();
    }
}
