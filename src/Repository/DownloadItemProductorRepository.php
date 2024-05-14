<?php

namespace App\Repository;

use App\Entity\DownloadItemProductor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DownloadItemProductor>
 *
 * @method DownloadItemProductor|null find($id, $lockMode = null, $lockVersion = null)
 * @method DownloadItemProductor|null findOneBy(array $criteria, array $orderBy = null)
 * @method DownloadItemProductor[]    findAll()
 * @method DownloadItemProductor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadItemProductorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DownloadItemProductor::class);
    }

//    /**
//     * @return DownloadItemProductor[] Returns an array of DownloadItemProductor objects
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

//    public function findOneBySomeField($value): ?DownloadItemProductor
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

//JSON_EXTRACT(`attributes` , '$.ports.usb') > 0

    /**
     * @return DownloadItemProductor[] Returns an array of DownloadItemProductor objects
     */
    public function findAllNormal(array $cities=[], $offset = 1,  $limit = 1000): array
    {
        
        $queryBuilder = $this->createQueryBuilder('d')
            ->andWhere("JSON_EXTRACT(d.dataBrut , '$.description') is not null")
            ->leftJoin('d.city', "c")
            //->setParameter('val', $value)
            //->orderBy('d.id', 'ASC')
            //->setMaxResults(10)
            //->setFirstResult(1)
            //->setMaxResults(1000)
        ;

        if ($cities && count($cities) > 0 ) {
            //dd($filterUserDto->getCities());
            
            $queryBuilder->andWhere('c is not null and c.id IN (:cities)');
            $queryBuilder->setParameter('cities', $cities);
        }
    
        $queryBuilder->setFirstResult($offset);

        return $queryBuilder->setMaxResults($limit)
                ->getQuery()
                ->getResult()
        ;
    }

}
