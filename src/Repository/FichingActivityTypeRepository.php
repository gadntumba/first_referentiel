<?php

namespace App\Repository;

use App\Entity\FichingActivityType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FichingActivityType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichingActivityType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichingActivityType[]    findAll()
 * @method FichingActivityType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichingActivityTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichingActivityType::class);
    }

    // /**
    //  * @return FichingActivityType[] Returns an array of FichingActivityType objects
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
    public function findOneBySomeField($value): ?FichingActivityType
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
