<?php

namespace App\Repository\EntrepreneurialActivity;

use App\Entity\EntrepreneurialActivity\ProductDisplayMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductDisplayMode>
 *
 * @method ProductDisplayMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDisplayMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDisplayMode[]    findAll()
 * @method ProductDisplayMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDisplayModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDisplayMode::class);
    }

//    /**
//     * @return ProductDisplayMode[] Returns an array of ProductDisplayMode objects
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

//    public function findOneBySomeField($value): ?ProductDisplayMode
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
