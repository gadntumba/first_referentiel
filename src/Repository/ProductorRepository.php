<?php

namespace App\Repository;

use App\Entity\AgriculturalActivity;
use App\Entity\FichingActivity;
use App\Entity\FichingActivityType;
use App\Entity\Monitor;
use App\Entity\OT;
use App\Entity\Productor;
use App\Entity\StockRaisingActivity;
use App\Entity\Supervisor;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Migrations\Query\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Productor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Productor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Productor[]    findAll()
 * @method Productor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductorRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 30;

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
     * 
     */
    public function customFindAll($limit = 30)
    {
        return $this->createQueryBuilder('p')
            ->join('p.levelStudy', "l")
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
        
    }


    /**
     * @return Productor[] Returns an array of Productor objects
     * 
     */
    public function findByOt(OT $ot, $limit = 30)
    {
        return $this->createQueryBuilder('p')
            ->join('p.monitor', "m")
            ->join('m.ot', "o")
            ->andWhere('o.id = :otID')
            ->setParameter('otID', $ot->getId())
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
        
    }


    /**
     * @return Productor[] Returns an array of Productor objects
     * 
     */
    public function findByMonitor(Monitor $monitor, $limit = 30)
    {
        return $this->createQueryBuilder('p')
            ->join('p.monitor', "m")
            ->andWhere('m.id = :monitorId')
            ->setParameter('monitorId', $monitor->getId())
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
        
    }


    /**
     * @return Productor[] Returns an array of Productor objects
     * 
     */
    public function findBySupervisor(Supervisor $supervisor, $limit = 30)
    {
        return $this->createQueryBuilder('p')
            ->join('p.monitor', "m")
            ->join('m.supervisor', "s")
            ->andWhere('s.id = :supervisorID')
            ->setParameter('supervisorID', $supervisor->getId())
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
        
    }


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
                    WHERE  YEARWEEK(p.created_at, 1) = YEARWEEK(:curr_date, 1) OR 
                            YEARWEEK(p.created_at, 1) = YEARWEEK(:curr_date, 1)-1
                    GROUP BY me_date;
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['curr_date' => $formatedDate]);
        
        $arrData = $resultSet->fetchAllAssociative();

        //dd($arrData);

        return $arrData;

    }



    /**
    * @return array Returns an array of Productor objects
    */
    public function findWeekStatsAgricultiral(DateTimeInterface $date)
    {
        $em = $this->getEntityManager();

        $formatedDate = $date->format("Y-m-d");

        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $otherTableName = $em->getClassMetadata(AgriculturalActivity::class)->getTableName();

        

        $sql = "SELECT DATE_FORMAT(p.created_at, '%Y-%m-%d') me_date, COUNT(DISTINCT(p.id)) nbr 
                    FROM $tableName p 
                    INNER JOIN $otherTableName o on o.productor_id = p.id 
                    WHERE  YEARWEEK(p.created_at, 1) = YEARWEEK(:curr_date, 1) OR 
                            YEARWEEK(p.created_at, 1) = YEARWEEK(:curr_date, 1)-1
                    GROUP BY me_date;
        ";
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['curr_date' => $formatedDate]);
        
        $arrData = $resultSet->fetchAllAssociative();

        //dd($arrData);

        return $arrData;

    }

    /**
    * @return array Returns an array of Productor objects
    */
    public function findWeekStatsStockRaisingActivity(DateTimeInterface $date)
    {
        $em = $this->getEntityManager();

        $formatedDate = $date->format("Y-m-d");

        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $otherTableName = $em->getClassMetadata(StockRaisingActivity::class)->getTableName();

        

        $sql = "SELECT DATE_FORMAT(p.created_at, '%Y-%m-%d') me_date,  COUNT(DISTINCT(p.id)) nbr 
                    FROM $tableName p 
                    INNER JOIN $otherTableName o on o.productor_id = p.id 
                    WHERE  YEARWEEK(p.created_at, 1) = YEARWEEK(:curr_date, 1) OR 
                            YEARWEEK(p.created_at, 1) = YEARWEEK(:curr_date, 1)-1
                    GROUP BY me_date;
        ";
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['curr_date' => $formatedDate]);
        
        $arrData = $resultSet->fetchAllAssociative();

        //dd($arrData);

        return $arrData;

    }

    /**
    * @return array Returns an array of Productor objects
    */
    public function findWeekStatsFichingActivity(DateTimeInterface $date)
    {
        $em = $this->getEntityManager();

        $formatedDate = $date->format("Y-m-d");

        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $otherTableName = $em->getClassMetadata(FichingActivity::class)->getTableName();

        

        $sql = "SELECT DATE_FORMAT(p.created_at, '%Y-%m-%d') me_date, COUNT(DISTINCT(p.id)) nbr 
                    FROM $tableName p 
                    INNER JOIN $otherTableName o on o.productor_id = p.id 
                    WHERE  YEARWEEK(p.created_at, 1) = YEARWEEK(:curr_date, 1) OR 
                            YEARWEEK(p.created_at, 1) = YEARWEEK(:curr_date, 1)-1
                    GROUP BY me_date;
        ";
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['curr_date' => $formatedDate]);
        
        $arrData = $resultSet->fetchAllAssociative();

        //dd($arrData);

        return $arrData;

    }

    /**
    * @return int count a productor agricultor
    */
    public function countAgriculturalActivity()
    {
        $em = $this->getEntityManager();


        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $actityTableName = $em->getClassMetadata(AgriculturalActivity::class)->getTableName();

        $sql = "SELECT count(p.id) nbr FROM $tableName p WHERE p.id in (SELECT act.productor_id FROM $actityTableName act);
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([]);
        
        $arrData = $resultSet->fetchAssociative();

        //dd($arrData);

        return $arrData;

    }

    /**
    * @return int count a productor agricultor
    */
    public function countFichingActivity()
    {
        $em = $this->getEntityManager();

        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $actityTableName = $em->getClassMetadata(FichingActivity::class)->getTableName();

        $sql = "SELECT count(p.id) nbr FROM $tableName p WHERE p.id in (SELECT act.productor_id FROM $actityTableName act);
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([]);
        
        $arrData = $resultSet->fetchAssociative();

        //dd($arrData);

        return $arrData;

    }


    /**
    * @return int count a productor agricultor
    */
    public function countStockRaisingActivity()
    {
        $em = $this->getEntityManager();

        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $actityTableName = $em->getClassMetadata(StockRaisingActivity::class)->getTableName();

        $sql = "SELECT count(p.id) nbr FROM $tableName p WHERE p.id in (SELECT act.productor_id FROM $actityTableName act);
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([]);
        
        $arrData = $resultSet->fetchAssociative();

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

    /**
    * @return int count a productor agricultor
    */
    public function countByMonitor(Monitor $monitor)
    {
        $em = $this->getEntityManager();

        $monitorId = $monitor->getId();
        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $monitorTableName = $em->getClassMetadata(Monitor::class)->getTableName();

        $sql = "SELECT count(p.id) nbr 
                    FROM $tableName p 
                    INNER JOIN $monitorTableName o ON o.id = p.monitor_id
                    WHERE p.monitor_id = :monitorId
                ;
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(["monitorId" => $monitorId]);
        
        $arrData = $resultSet->fetchAssociative();

        //dd($arrData);

        return $arrData;

    }


    /**
    * @return int count a productor agricultor
    */
    public function countByOT(OT $ot)
    {
        $em = $this->getEntityManager();

        $otId = $ot->getId();
        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $monitorTableName = $em->getClassMetadata(Monitor::class)->getTableName();
        $otTableName = $em->getClassMetadata(OT::class)->getTableName();

        $sql = "SELECT count(p.id) nbr 
                    FROM $tableName p 
                    INNER JOIN $monitorTableName m ON m.id = p.monitor_id
                    INNER JOIN $otTableName o ON o.id = m.ot_id
                    WHERE m.ot_id = :otId
                ;
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(["otId" => $otId]);
        
        $arrData = $resultSet->fetchAssociative();

        //dd($arrData);

        return $arrData;

    }


    /**
    * @return int count a productor agricultor
    */
    public function countBySupervisor(Supervisor $supervisor)
    {
        $em = $this->getEntityManager();

        $supervisorId = $supervisor->getId();
        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $monitorTableName = $em->getClassMetadata(Monitor::class)->getTableName();
        $supervisorTableName = $em->getClassMetadata(Supervisor::class)->getTableName();

        $sql = "SELECT count(p.id) nbr 
                    FROM $tableName p 
                    INNER JOIN $monitorTableName m ON m.id = p.monitor_id
                    INNER JOIN $supervisorTableName s ON s.id = m.supervisor_id
                    WHERE m.supervisor_id = :supervisorId
                ;
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(["supervisorId" => $supervisorId]);
        
        $arrData = $resultSet->fetchAssociative();

        //dd($arrData);

        return $arrData;

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

     /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     */
    
}
