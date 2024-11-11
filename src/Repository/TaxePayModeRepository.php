<?php

namespace App\Repository;

use App\Entity\TaxePayMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaxePayMode>
 *
 * @method TaxePayMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxePayMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxePayMode[]    findAll()
 * @method TaxePayMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxePayModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxePayMode::class);
    }

//    /**
//     * @return TaxePayMode[] Returns an array of TaxePayMode objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TaxePayMode
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
