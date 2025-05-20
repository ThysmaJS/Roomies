<?php
namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\GameResultStatus;


#[ORM\Entity]
#[ApiResource]

class GameResult {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Room::class)]
    private ?Room $room;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user;

    #[ORM\Column(type: "string", enumType: GameResultStatus::class)]
    private GameResultStatus $result;

    #[ORM\Column(type: "integer")]
    private int $score;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;
}
