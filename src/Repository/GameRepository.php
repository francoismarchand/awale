<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findOneByUuid(string $uuid): ?Game
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT p
            FROM App\Entity\Game
            WHERE p.uuid = :uuid
        ');

        $query->execute([
            'uuid' => $uuid
        ]);

        return $query->getOneOrNullResult();
    }
}
