<?php

namespace App\Repository;

use App\Entity\Partie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Partie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partie[]    findAll()
 * @method Partie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partie::class);
    }

    public function findOneByUuid(string $uuid): ?Partie
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT p
            FROM App\Entity\Partie
            WHERE p.uuid = :uuid
        ');

        $query->execute([
            'uuid' => $uuid
        ]);

        return $query->getOneOrNullResult();
    }
}
