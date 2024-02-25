<?php

namespace App\Repository;

use App\Entity\Instigator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Instigator>
 *
 * @method Instigator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instigator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instigator[]    findAll()
 * @method Instigator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstigatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instigator::class);
    }

//    /**
//     * @return Instigator[] Returns an array of Instigator objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Instigator
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
