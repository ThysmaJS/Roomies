<?php
namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\DataPersister\RoomDataPersister;


#[ORM\Entity]

#[ApiResource(
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


class Room {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(type: "integer")]
    private int $maxPlayers;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $owner = null;
    

    #[ORM\ManyToOne(targetEntity: Game::class)]
    private ?Game $currentGame;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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
}
