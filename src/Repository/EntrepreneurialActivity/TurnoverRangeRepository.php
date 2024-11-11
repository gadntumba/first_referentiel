<?php

namespace App\Repository\EntrepreneurialActivity;

use App\Entity\EntrepreneurialActivity\TurnoverRange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TurnoverRange>
 *
 * @method TurnoverRange|null find($id, $lockMode = null, $lockVersion = null)
 * @method TurnoverRange|null findOneBy(array $criteria, array $orderBy = null)
 * @method TurnoverRange[]    findAll()
 * @method TurnoverRange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurnoverRangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurnoverRange::class);
    }

//    /**
//     * @return TurnoverRange[] Returns an array of TurnoverRange objects
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

//    public function findOneBySomeField($value): ?TurnoverRange
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
