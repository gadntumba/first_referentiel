<?php

namespace App\Repository;

use App\Entity\NFC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NFC|null find($id, $lockMode = null, $lockVersion = null)
 * @method NFC|null findOneBy(array $criteria, array $orderBy = null)
 * @method NFC[]    findAll()
 * @method NFC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NFCRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NFC::class);
    }

    // /**
    //  * @return NFC[] Returns an array of NFC objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NFC
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
