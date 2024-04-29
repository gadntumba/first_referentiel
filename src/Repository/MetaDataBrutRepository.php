<?php

namespace App\Repository;

use App\Entity\MetaDataBrut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MetaDataBrut>
 *
 * @method MetaDataBrut|null find($id, $lockMode = null, $lockVersion = null)
 * @method MetaDataBrut|null findOneBy(array $criteria, array $orderBy = null)
 * @method MetaDataBrut[]    findAll()
 * @method MetaDataBrut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetaDataBrutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MetaDataBrut::class);
    }

//    /**
//     * @return MetaDataBrut[] Returns an array of MetaDataBrut objects
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

//    public function findOneBySomeField($value): ?MetaDataBrut
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
