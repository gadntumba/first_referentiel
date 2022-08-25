<?php

namespace App\Repository;

use App\Entity\StockRaisingActivityTypeGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockRaisingActivityTypeGroup>
 *
 * @method StockRaisingActivityTypeGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockRaisingActivityTypeGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockRaisingActivityTypeGroup[]    findAll()
 * @method StockRaisingActivityTypeGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRaisingActivityTypeGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockRaisingActivityTypeGroup::class);
    }

    public function add(StockRaisingActivityTypeGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StockRaisingActivityTypeGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return StockRaisingActivityTypeGroup[] Returns an array of StockRaisingActivityTypeGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StockRaisingActivityTypeGroup
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
