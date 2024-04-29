<?php

namespace App\Repository;

use App\Entity\DataBrut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataBrut>
 *
 * @method DataBrut|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataBrut|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataBrut[]    findAll()
 * @method DataBrut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataBrutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataBrut::class);
    }

//    /**
//     * @return DataBrut[] Returns an array of DataBrut objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DataBrut
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
