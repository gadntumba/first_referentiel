<?php

namespace App\Repository;

use App\Entity\FichingActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FichingActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichingActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichingActivity[]    findAll()
 * @method FichingActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichingActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichingActivity::class);
    }

    // /**
    //  * @return FichingActivity[] Returns an array of FichingActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FichingActivity
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
