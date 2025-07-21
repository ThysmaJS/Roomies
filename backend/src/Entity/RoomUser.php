<?php

namespace App\Entity;

use App\Repository\RoomUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomUserRepository::class)]
class RoomUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['room:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: "roomUsers")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['room:read'])]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['room:read'])]
    private \DateTimeInterface $joinedAt;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['room:read'])]
    private bool $isReady = false;

    public function __construct() { $this->joinedAt = new \DateTime(); }

    public function getId(): ?int { return $this->id; }

    public function getRoom(): ?Room { return $this->room; }
    public function setRoom(Room $room): void { $this->room = $room; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(User $user): void { $this->user = $user; }

    public function getJoinedAt(): \DateTimeInterface { return $this->joinedAt; }
    public function setJoinedAt(\DateTimeInterface $joinedAt): void { $this->joinedAt = $joinedAt; }

    public function isReady(): bool { return $this->isReady; }
    public function setIsReady(bool $isReady): void { $this->isReady = $isReady; }
}
