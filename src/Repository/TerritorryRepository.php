<?php

namespace App\Repository;

use App\Entity\Territorry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Territorry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Territorry|null findOneBy(array $criteria, array $orderBy = null)
 * @method Territorry[]    findAll()
 * @method Territorry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerritorryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Territorry::class);
    }

    // /**
    //  * @return Territorry[] Returns an array of Territorry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Territorry
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
