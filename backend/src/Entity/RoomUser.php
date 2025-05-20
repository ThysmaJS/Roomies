<?php
namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ApiResource]

#[ORM\UniqueConstraint(columns: ["room_id", "user_id"])]
class RoomUser {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Room::class)]
    private ?Room $room;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $joinedAt;
}
