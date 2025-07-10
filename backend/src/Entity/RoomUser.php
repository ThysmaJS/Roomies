<?php

// src/Entity/RoomUser.php
namespace App\Entity;

use App\Repository\RoomUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomUserRepository::class)]
class RoomUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Room::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $joinedAt;

    public function __construct()
    {
        $this->joinedAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }

    public function getRoom(): ?Room { return $this->room; }
    public function setRoom(Room $room): void { $this->room = $room; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(User $user): void { $this->user = $user; }

    public function getJoinedAt(): \DateTimeInterface { return $this->joinedAt; }
    public function setJoinedAt(\DateTimeInterface $joinedAt): void { $this->joinedAt = $joinedAt; }
}
