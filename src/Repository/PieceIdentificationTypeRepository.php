<?php

namespace App\Repository;

use App\Entity\PieceIdentificationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PieceIdentificationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PieceIdentificationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PieceIdentificationType[]    findAll()
 * @method PieceIdentificationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PieceIdentificationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PieceIdentificationType::class);
    }

    // /**
    //  * @return PieceIdentificationType[] Returns an array of PieceIdentificationType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PieceIdentificationType
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
