<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\PartieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartieRepository::class)
 */
class Partie implements \JsonSerializable
{
    const STATUS_PREPARE = 1;
    const STATUS_READY = 2;
    const STATUS_IN_PROGRESS = 3;
    const STATUS_FINISHED = 4;

    const PLAYER_1 = 0;
    const PLAYER_2 = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentPlayer;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="winner_user_id", referencedColumnName="id", nullable=true)
    */
    private $winner;

    /**
     * @ORM\Column(type="json")
     */
    private $scores;

    /**
     * @ORM\Column(type="json")
     */
    private $board = [];

    /**
     * @ORM\Column(type="date")
     */
    private $date_creation;




    public function __construct()
    {
        $this->status = self::STATUS_PREPARE;
        $this->currentPlayer = self::PLAYER_1;
        $this->scores = [
            self::PLAYER_1 => 0,
            self::PLAYER_2 => 0,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCurrentPlayer(): int
    {
        return $this->currentPlayer;
    }

    public function setCurrentPlayer(int $currentPlayer): self
    {
        $this->currentPlayer = $currentPlayer;
        

        return $this;
    }

    public function getWinner(): ?User
    {
        return $this->winner;
    }

    public function setWinner(?User $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getScores(): array
    {
        return $this->scores;
    }

    public function setScores(array $scores): self
    {
        $this->scores = $scores;

        return $this;
    }

    public function addScore(int $player, int $points): self
    {
        $this->scores[$player] += $points;

        return $this;
    }

    public function getBoard(): ?array
    {
        return $this->board;
    }

    public function setBoard(array $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'status' => $this->status,
            'board' => $this->board,
            'scores' => $this->scores
        ];
    }
}
