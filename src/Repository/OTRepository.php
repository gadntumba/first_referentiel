<?php

namespace App\Repository;

use App\Entity\OT;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OT|null find($id, $lockMode = null, $lockVersion = null)
 * @method OT|null findOneBy(array $criteria, array $orderBy = null)
 * @method OT[]    findAll()
 * @method OT[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OTRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OT::class);
    }

    // /**
    //  * @return OT[] Returns an array of OT objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OT
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
