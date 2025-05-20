<?php
namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\FriendStatus;


#[ORM\Entity]
#[ApiResource]

class Friend {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $friend;

    #[ORM\Column(type: "string", enumType: FriendStatus::class)]
    private FriendStatus $status;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;
}
