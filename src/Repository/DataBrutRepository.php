<?php

namespace App\Repository;

use App\Entity\DataBrut;
use App\Entity\MetaDataBrut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataBrut>
 *
 * @method DataBrut|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataBrut|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataBrut[]    findAll()
 * @method DataBrut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataBrutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataBrut::class);
    }

//    /**
//     * @return DataBrut[] Returns an array of DataBrut objects
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

//    public function findOneBySomeField($value): ?DataBrut
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    /**
     * 
     */
    public function findLasRowId(MetaDataBrut $metaData): ?array
    {
        return $this->createQueryBuilder('d')
            ->select('max(d.rowId)')
            ->JOIN('d.mataData', 'md')
            ->andWhere('md.id = :mataData')
            //->andWhere('d.rowId = :rowId')
            ->setParameter('mataData', $metaData->getId())
            //->setParameter('rowId', $rowId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    /**
     * 
     */
    public function findByGroups(): ?array
    {
        return $this->createQueryBuilder('d')
            ->select("JSON_EXTRACT(d.content , '$.organization') org, md.cheetTitle, count(d.id) total")
            ->JOIN('d.mataData', 'md')
            //->andWhere('md.id = :mataData')
            //->andWhere('d.rowId = :rowId')
            //->setParameter('mataData', $metaData->getId())
            //->setParameter('rowId', $rowId)
            //JSON_EXTRACT(`content` , '$.organization')
            ->groupBy('org','md.cheetTitle')
            ->orderBy('org')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * 
     */
    public function findByMetadataCityName(string $cityName): ?array
    {
        return $this->createQueryBuilder('d')
            //->select("JSON_EXTRACT(d.content , '$.organization') org, md.cheetTitle, count(d.id) total")
            ->JOIN('d.mataData', 'md')
            ->andWhere('md.cityName = :cityName')
            //->andWhere('d.rowId = :rowId')
            ->setParameter('cityName', $cityName)
            //->setParameter('rowId', $rowId)
            //JSON_EXTRACT(`content` , '$.organization')
            //->groupBy('org','md.cheetTitle')
            //->orderBy('org')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * 
     */
    public function findByCities(): ?array
    {
        return $this->createQueryBuilder('d')
            ->select("md.cityName cityName, md.cheetTitle, count(d.id) total")
            ->JOIN('d.mataData', 'md')
            //->andWhere('md.id = :mataData')
            //->andWhere('d.rowId = :rowId')
            //->setParameter('mataData', $metaData->getId())
            //->setParameter('rowId', $rowId)
            //JSON_EXTRACT(`content` , '$.organization')
            ->groupBy('md.cityName','md.cheetTitle')
            ->orderBy('md.cityName')
            ->getQuery()
            ->getResult()
        ;
    }
}
