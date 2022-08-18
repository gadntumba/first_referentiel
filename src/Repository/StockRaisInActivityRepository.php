<?php

namespace App\Repository;

use App\Entity\StockRaisInActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockRaisInActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockRaisInActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockRaisInActivity[]    findAll()
 * @method StockRaisInActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRaisInActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockRaisInActivity::class);
    }

    // /**
    //  * @return StockRaisInActivity[] Returns an array of StockRaisInActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StockRaisInActivity
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
