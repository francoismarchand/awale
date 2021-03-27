<?php
namespace App\Factory;

use App\Entity\Partie;

class PartieFactory
{
    public function create(): Partie
    {
        return (new Partie())
            ->setUuid(\uniqid() . \uniqid())
            ->setStatus(Partie::STATUS_PREPARE)
            ->setPlateau([
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
            ])
            ->setDateCreation(new \DateTime())
        ;
    }
}
