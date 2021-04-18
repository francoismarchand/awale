<?php
namespace App\Entity;

use App\Entity\Partie;

class Board implements \JsonSerializable 
{
    private $data = [
        Partie::PLAYER_1 => [
            0 => 4,
            1 => 4,
            2 => 4,
            3 => 4,
            4 => 4,
            5 => 4
        ],
        Partie::PLAYER_2 => [
            0 => 4,
            1 => 4,
            2 => 4,
            3 => 4,
            4 => 4,
            5 => 4
        ]
    ];

    public function getBoard(): array
    {
        return $this->board;
    }

    public function setBoard(array $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return  $this->data;
    }
}