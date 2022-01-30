<?php

namespace App\Repository;

use App\Entity\AgriculturalActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AgriculturalActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgriculturalActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgriculturalActivity[]    findAll()
 * @method AgriculturalActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgriculturalActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgriculturalActivity::class);
    }

    // /**
    //  * @return AgriculturalActivity[] Returns an array of AgriculturalActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AgriculturalActivity
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
