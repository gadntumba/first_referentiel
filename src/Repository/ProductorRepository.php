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
use App\Dto\FilterUserDto;
use Doctrine\ORM\Tools\Pagination\Paginator;
use ApiPlatform\Doctrine\Orm\Paginator as ApiPlatformPaginator;
use Doctrine\Common\Collections\Criteria;

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
     * @return Productor[]
     */
    public function findNotLoad() : array 
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.remoteId is null')
            ->getQuery()
            ->getResult()
        ;
        
    }
    public function getBooksByFavoriteAuthor(FilterUserDto $filterUserDto = null, int $page = 1, bool $onlyActived = true): ApiPlatformPaginator
    {
        $firstResult = ($page -1) * self::PAGINATOR_PER_PAGE;

        $queryBuilder = $this->createQueryBuilder("u");
        $queryBuilder->select('u')
            ->leftJoin('u.housekeeping', 'h')
            ->leftJoin('h.address', 'a')
            ->leftJoin('a.town', 'to')
            ->leftJoin('a.sector', 's')
            ->leftJoin('to.city', 'c')
            ->leftJoin('s.territorry', 'te')
            ->leftJoin('te.province', 'pr')
            ->leftJoin('c.province', 'pu')
            /*->setParameter('author', $user->getFavoriteAuthor()->getId())
            ->andWhere('b.publicatedOn IS NOT NULL');*/
            ;   

            $queryBuilder->andWhere('u.isNormal = :normal');
            $queryBuilder->setParameter('normal', true);
            
        if ($onlyActived) {
            $queryBuilder->andWhere('u.isActive = :actived');
            $queryBuilder->setParameter('actived', true);
        }

        if($filterUserDto && $filterUserDto->getSearch()) {

            $search = strtolower($filterUserDto->getSearch()) ;
            $queryBuilder->andWhere('u.id LIKE :id OR lower(u.name) LIKE :name OR lower(u.lastname) LIKE :lastname OR lower(u.firstname) LIKE :firstname OR lower(u.phoneNumber) LIKE :phoneNumber')
                ->setParameter('id', "%" .$search. "%")
                ->setParameter('name', "%" .$search. "%")
                ->setParameter('lastname', "%" .$search. "%")
                ->setParameter('firstname', "%" .$search. "%")
                ->setParameter('phoneNumber', "%" .$search. "%")
            ;

        }
        if($filterUserDto && $filterUserDto->getDateStart() && $filterUserDto->getDateEnd()) {

            $queryBuilder->andWhere('u.createdAt BETWEEN :dateStart AND :dateEnd');
            $queryBuilder->setParameter('dateStart', $filterUserDto->getDateStart()->format("Y-m-d"));
            $queryBuilder->setParameter('dateEnd', $filterUserDto->getDateEnd()->format("Y-m-d"));

        }

        if ($filterUserDto && $filterUserDto->getTowns() && count($filterUserDto->getTowns()) > 0 ) {
            $queryBuilder->andWhere('to is not null and to.id IN (:towns)');
            $queryBuilder->setParameter('towns', $filterUserDto->getTowns());
        }

        if ($filterUserDto && $filterUserDto->getSectors() && count($filterUserDto->getSectors()) > 0 ) {
            $queryBuilder->andWhere('s is not null and s.id IN (:sectors)');
            $queryBuilder->setParameter('sectors', $filterUserDto->getSectors());
        }

        if ($filterUserDto && $filterUserDto->getCities() && count($filterUserDto->getCities()) > 0 ) {
            //dd($filterUserDto->getCities());
            $queryBuilder->andWhere('c is not null and c.id IN (:cities)');
            $queryBuilder->setParameter('cities', $filterUserDto->getCities());
        }

        if ($filterUserDto && $filterUserDto->getTerritories() && count($filterUserDto->getTerritories()) > 0 ) {
            $queryBuilder->andWhere('te is not null and te.id IN (:te)');
            $queryBuilder->setParameter('te', $filterUserDto->getTerritories());
        }

        if ($filterUserDto && $filterUserDto->getProvinces() && count($filterUserDto->getProvinces()) > 0 ) {
            $queryBuilder->andWhere('pr is not null and pr.id IN (:provs) or pu is not null and pu.id IN (:provs)');
            $queryBuilder->setParameter('provs', $filterUserDto->getProvinces());
        }

        $query=$queryBuilder->getQuery();
        //dump($query->getSQL());
        //dd($query->getParameters());
        //$query->getSQL();

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::PAGINATOR_PER_PAGE);
        $queryBuilder->addCriteria($criteria);

        $doctrinePaginator = new Paginator($queryBuilder);
        $paginator = new ApiPlatformPaginator($doctrinePaginator);

        return $paginator;

        /*$sub1 = $this->_em->createQueryBuilder();
        $sub1->from(User::class, "u1");

        if ($filterUserDto->getProvinces() && count($filterUserDto->getProvinces()) > 0 ) {
            Connection::PARAM_STR_ARRAY;
            $queryBuilder->andWhere("");
        }

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);
        $queryBuilder->addCriteria($criteria);

        $doctrinePaginator = new Paginator($queryBuilder);
        $paginator = new ApiPlatformPaginator($doctrinePaginator);

        return $paginator;*/
    }

    
}
