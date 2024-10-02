<?php

namespace App\Repository;

use App\Entity\ProductorPreload;
use App\Entity\ProductorPreloadDuplicate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductorPreloadDuplicate>
 *
 * @method ProductorPreloadDuplicate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductorPreloadDuplicate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductorPreloadDuplicate[]    findAll()
 * @method ProductorPreloadDuplicate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductorPreloadDuplicateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductorPreloadDuplicate::class);
    }

//    /**
//     * @return ProductorPreloadDuplicate[] Returns an array of ProductorPreloadDuplicate objects
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

//    public function findOneBySomeField($value): ?ProductorPreloadDuplicate
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    /**
     * @return ProductorPreloadDuplicate[] Returns an array of ProductorPreloadDuplicate objects
     */
    public function findDuplicatePossible(ProductorPreload $main, ProductorPreload $secondary): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.main', "m")
            ->innerJoin('p.secondary', "s")
            ->orWhere('m.id = :manId AND s.id = :secondaryId')
            ->orWhere('m.id = :secondaryId AND s.id = :manId')
            ->setParameter('manId', $main->getId())
            ->setParameter('secondaryId', $secondary->getId())
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return ProductorPreloadDuplicate[] Returns an array of ProductorPreloadDuplicate objects
     */
    public function findNoConfirm(): array
    {
        //setSetNotDuplicateAt
        return $this->createQueryBuilder('p')
            ->andWhere('p.confirmAt is null and p.setNotDuplicateAt is null')
            ->orderBy('p.id', 'ASC')
            //->setMaxResults(2)
            ->getQuery()
            ->getResult()
        ;
    }

}
