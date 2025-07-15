<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['room:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['room:read'])]
    private string $name;

    #[ORM\Column]
    #[Assert\Positive]
    #[Groups(['room:read'])]
    private int $maxPlayers;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['room:read'])]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: RoomUser::class, cascade: ['persist', 'remove'])]
    #[Groups(['room:read'])]
    private Collection $roomUsers;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['room:read'])]
    private ?string $currentGame = null;


    public function __construct()
    {
        $this->roomUsers = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getMaxPlayers(): int { return $this->maxPlayers; }
    public function setMaxPlayers(int $maxPlayers): void { $this->maxPlayers = $maxPlayers; }

    public function getOwner(): ?User { return $this->owner; }
    public function setOwner(User $owner): void { $this->owner = $owner; }

    public function getRoomUsers(): Collection { return $this->roomUsers; }
    public function addRoomUser(RoomUser $roomUser): void { $this->roomUsers->add($roomUser); }

    public function getCurrentGame(): ?string { return $this->currentGame; }
    public function setCurrentGame(?string $game): void { $this->currentGame = $game; }
}
