<?php

namespace App\Repository;

use App\Entity\StockRaisingActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockRaisingActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockRaisingActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockRaisingActivity[]    findAll()
 * @method StockRaisingActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRaisingActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockRaisingActivity::class);
    }

    // /**
    //  * @return StockRaisingActivity[] Returns an array of StockRaisingActivity objects
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
    public function findOneBySomeField($value): ?StockRaisingActivity
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
