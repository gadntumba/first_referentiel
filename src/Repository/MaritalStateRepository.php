<?php

namespace App\Repository;

use App\Entity\MaritalState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaritalState>
 *
 * @method MaritalState|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaritalState|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaritalState[]    findAll()
 * @method MaritalState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaritalStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaritalState::class);
    }

//    /**
//     * @return MaritalState[] Returns an array of MaritalState objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MaritalState
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
