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
            ->setBoard([
                0 => 4,
                1 => 4,
                2 => 4,
                3 => 4,
                4 => 4,
                5 => 4,
                6 => 4,
                7 => 4,
                8 => 4,
                9 => 4,
                10 => 4,
                11 => 4
            ])
            ->setDateCreation(new \DateTime())
        ;
    }
}
