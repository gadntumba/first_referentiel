<?php

namespace App\Repository;

use App\Entity\ProductorBrut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductorBrut>
 *
 * @method ProductorBrut|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductorBrut|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductorBrut[]    findAll()
 * @method ProductorBrut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductorBrutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductorBrut::class);
    }

//    /**
//     * @return ProductorBrut[] Returns an array of ProductorBrut objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductorBrut
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
