<?php

namespace App\Repository;

use App\Entity\DocumentBrut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocumentBrut>
 *
 * @method DocumentBrut|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentBrut|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentBrut[]    findAll()
 * @method DocumentBrut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentBrutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentBrut::class);
    }

//    /**
//     * @return DocumentBrut[] Returns an array of DocumentBrut objects
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

//    public function findOneBySomeField($value): ?DocumentBrut
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
