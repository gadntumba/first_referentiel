<?php

namespace App\Repository;

use App\Entity\Productor;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Productor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Productor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Productor[]    findAll()
 * @method Productor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Productor::class);
    }

    // /**
    //  * @return Productor[] Returns an array of Productor objects
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
    /**
    * @return Productor[] Returns an array of Productor objects
    */
    public function findBySmartphone(int $smartId)
    {
        return $this->createQueryBuilder('p')
            ->join('p.smartphone', "s")
            ->andWhere('s.id = :smart_id')
            ->setParameter('smart_id', $smartId)
            ->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return array Returns an array of Productor objects
    */
    public function findWeekStats(DateTimeInterface $date)
    {
        $em = $this->getEntityManager();

        $formatedDate = $date->format("Y-m-d");

        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();

        $sql = "SELECT DATE_FORMAT(p.created_at, '%Y-%m-%d') me_date, COUNT(id) nbr 
                    FROM $tableName p 
                    WHERE  YEARWEEK(p.created_at) = YEARWEEK(:curr_date) OR 
                            YEARWEEK(p.created_at)-1 = YEARWEEK(:curr_date) 
                    GROUP BY me_date;
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['curr_date' => $formatedDate]);
        
        $arrData = $resultSet->fetchAllAssociative();

        //dd($arrData);

        return $arrData;

    }

    ///**
    // * Count all producers
    // * @return int
    // */
    /*public function count()
    {
        
    }*/

    public function findByImei(string $imei)
    {
        return $this->createQueryBuilder('p')
            ->join('p.smartphone', "s")
            ->andWhere('s.imei = :smart_imei')
            ->setParameter('smart_imei', $imei)
            ->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Productor
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
