<?php 

// src/Entity/Room.php
namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column]
    #[Assert\Positive]
    private int $maxPlayers;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getMaxPlayers(): int { return $this->maxPlayers; }
    public function setMaxPlayers(int $maxPlayers): void { $this->maxPlayers = $maxPlayers; }

    public function getOwner(): ?User { return $this->owner; }
    public function setOwner(User $owner): void { $this->owner = $owner; }
}
