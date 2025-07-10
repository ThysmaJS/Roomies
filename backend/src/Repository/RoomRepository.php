<?php

// src/Repository/RoomRepository.php
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

    public function findByOwner($owner): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.owner = :owner')
            ->setParameter('owner', $owner)
            ->getQuery()
            ->getResult();
    }
}
