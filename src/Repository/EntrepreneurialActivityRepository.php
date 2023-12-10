<?php

namespace App\Repository;

use App\Entity\EntrepreneurialActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EntrepreneurialActivity>
 *
 * @method EntrepreneurialActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntrepreneurialActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntrepreneurialActivity[]    findAll()
 * @method EntrepreneurialActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepreneurialActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntrepreneurialActivity::class);
    }

//    /**
//     * @return EntrepreneurialActivity[] Returns an array of EntrepreneurialActivity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EntrepreneurialActivity
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
