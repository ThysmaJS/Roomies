<?php
namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]

class GameState {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Room::class)]
    private ?Room $room;

    #[ORM\Column(type: "json")]
    private array $stateJson;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updatedAt;
}
