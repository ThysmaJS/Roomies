<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\DataPersister\RoomUserDataPersister;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['room:read']],
    processor: RoomUserDataPersister::class
)]
#[ORM\UniqueConstraint(columns: ["room_id", "user_id"])]
class RoomUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['room:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: "roomUsers", targetEntity: Room::class)]
    private ?Room $room = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['room:read'])]
    private ?User $user = null;

    #[ORM\Column(type: "datetime")]
    #[Groups(['room:read'])]
    private \DateTimeInterface $joinedAt;

    #[ORM\Column(type: "boolean")]
    #[Groups(['room:read'])]
    private bool $isReady = false;

    public function __construct()
    {
        $this->joinedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getJoinedAt(): \DateTimeInterface
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeInterface $joinedAt): self
    {
        $this->joinedAt = $joinedAt;
        return $this;
    }

    public function isReady(): bool
    {
        return $this->isReady;
    }

    public function setIsReady(bool $ready): self
    {
        $this->isReady = $ready;
        return $this;
    }
}
