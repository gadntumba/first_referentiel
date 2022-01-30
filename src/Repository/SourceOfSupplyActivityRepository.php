<?php

namespace App\Repository;

use App\Entity\SourceOfSupplyActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SourceOfSupplyActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SourceOfSupplyActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SourceOfSupplyActivity[]    findAll()
 * @method SourceOfSupplyActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceOfSupplyActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SourceOfSupplyActivity::class);
    }

    // /**
    //  * @return SourceOfSupplyActivity[] Returns an array of SourceOfSupplyActivity objects
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
    public function findOneBySomeField($value): ?SourceOfSupplyActivity
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
