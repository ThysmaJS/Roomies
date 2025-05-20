<?php
namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]

class User {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $username;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $password;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarUrl = null;

    #[ORM\Column(type: "integer")]
    private int $score = 0;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: Role::class)]
    private ?Role $role = null;

    #[ORM\OneToMany(mappedBy: "owner", targetEntity: Room::class)]
    private Collection $ownedRooms;

    #[ORM\OneToMany(mappedBy: "sender", targetEntity: Message::class)]
    private Collection $messages;
}
