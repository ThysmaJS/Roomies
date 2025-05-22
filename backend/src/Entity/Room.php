<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\DataPersister\RoomDataPersister;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['room:read']],
    operations: [
        new \ApiPlatform\Metadata\Post(
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            processor: RoomDataPersister::class
        ),
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Delete(security: "object.getOwner() == user")
    ]
)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['room:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['room:read'])]
    private string $name;

    #[ORM\Column(type: "integer")]
    #[Groups(['room:read'])]
    private int $maxPlayers;

    #[ORM\Column(type: "datetime", nullable: true)]
    #[Groups(['room:read'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['room:read'])]
    private ?User $owner = null;

    #[ORM\ManyToOne(targetEntity: Game::class)]
    #[Groups(['room:read'])]
    private ?Game $currentGame = null;

    #[ORM\OneToMany(mappedBy: "room", targetEntity: RoomUser::class)]
    #[Groups(['room:read'])]
    private Collection $roomUsers;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->roomUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers(int $maxPlayers): self
    {
        $this->maxPlayers = $maxPlayers;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    public function getCurrentGame(): ?Game
    {
        return $this->currentGame;
    }

    public function setCurrentGame(?Game $currentGame): self
    {
        $this->currentGame = $currentGame;
        return $this;
    }

    public function getRoomUsers(): Collection
    {
        return $this->roomUsers;
    }

    public function addRoomUser(RoomUser $roomUser): self
    {
        if (!$this->roomUsers->contains($roomUser)) {
            $this->roomUsers[] = $roomUser;
            $roomUser->setRoom($this);
        }

        return $this;
    }

    public function removeRoomUser(RoomUser $roomUser): self
    {
        if ($this->roomUsers->removeElement($roomUser)) {
            // set the owning side to null (unless already changed)
            if ($roomUser->getRoom() === $this) {
                $roomUser->setRoom(null);
            }
        }

        return $this;
    }
}
