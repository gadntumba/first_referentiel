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
use DateTime;
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
    public function getBooksByFavoriteAuthor(
        FilterUserDto $filterUserDto = null, 
        int $page = 1, bool $onlyActived = true, 
        bool $isTest=true, bool $isInvestigator=false, $user=null
    ): ApiPlatformPaginator
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
            //dd($isTest);
            if (!$isTest) {
                //dd(!$isTest);
                $queryBuilder->andWhere('u.isNormal = :normal');
                $queryBuilder->setParameter('normal', true);
            }

        if ($onlyActived) {
            $queryBuilder->andWhere('u.isActive = :actived');
            $queryBuilder->setParameter('actived', true);
        }

        if ($isInvestigator && !$onlyActived) 
        {
            //dd($user?->getNormalUsername());
            $queryBuilder->andWhere('u.isActive is null');
            $queryBuilder->andWhere('u.investigatorId = :investigatorId');
            $queryBuilder->setParameter('investigatorId', $user?->getNormalUsername());   
            //$queryBuilder->setParameter('actived', false);         
        }

        if($filterUserDto && $filterUserDto->getSearch()) {

            $search = strtolower($filterUserDto->getSearch()) ;
            $queryBuilder->andWhere('u.id LIKE :id OR lower(u.name) LIKE :name OR lower(u.lastName) LIKE :lastName OR lower(u.firstName) LIKE :firstName OR lower(u.phoneNumber) LIKE :phoneNumber')
                ->setParameter('id', "%" .$search. "%")
                ->setParameter('name', "%" .$search. "%")
                ->setParameter('lastName', "%" .$search. "%")
                ->setParameter('firstName', "%" .$search. "%")
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


    public function getBooksByFavoriteAuthorStats(
        FilterUserDto $filterUserDto = null, 
        int $page = 1, bool $onlyActived = true, 
        bool $isTest=true
    ): array
    {
        $firstResult = ($page -1) * self::PAGINATOR_PER_PAGE;

        $queryBuilder = $this->createQueryBuilder("u");

        $queryBuilder->select(
            "count(u.id) total, sum(case when u.isActive = 1 then 1 else 0 end) validated"
            //"u"
        )
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
            //dd($isTest);
            if (!$isTest) {
                //dd(!$isTest);
                $queryBuilder->andWhere('u.isNormal = :normal');
                $queryBuilder->setParameter('normal', true);
            }

        if ($onlyActived) {
            $queryBuilder->andWhere('u.isActive = :actived');
            $queryBuilder->setParameter('actived', true);
        }

        if($filterUserDto && $filterUserDto->getSearch()) {

            $search = strtolower($filterUserDto->getSearch()) ;
            $queryBuilder->andWhere('u.id LIKE :id OR lower(u.name) LIKE :name OR lower(u.lastName) LIKE :lastName OR lower(u.firstName) LIKE :firstName OR lower(u.phoneNumber) LIKE :phoneNumber')
                ->setParameter('id', "%" .$search. "%")
                ->setParameter('name', "%" .$search. "%")
                ->setParameter('lastName', "%" .$search. "%")
                ->setParameter('firstName', "%" .$search. "%")
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
        //$queryBuilder->groupBy("")
        $stats = $queryBuilder->getQuery()->getResult(); 
        return array_pop($stats);
        
    }




    public function getStatsAll(bool $isTest): array
    {

        $queryBuilder = $this->createQueryBuilder("u");

        $queryBuilder->select(
            "count(u.id) total, sum(case when u.isActive = 1 then 1 else 0 end) validated, sum(case when u.isActive is null then 1 else 0 end) invalidated"
            //"u"
        )
        
            /*->setParameter('author', $user->getFavoriteAuthor()->getId())
            ->andWhere('b.publicatedOn IS NOT NULL');*/
            ;   
        //$queryBuilder->groupBy("")
        if (!$isTest) {
            //dd(!$isTest);
            $queryBuilder->andWhere('u.isNormal = :normal');
            $queryBuilder->setParameter('normal', true);
        }
        $stats = $queryBuilder->getQuery()->getResult(); 
        return $stats;
        
    }
    public function getStatsCities(bool $isTest): array
    {

        $queryBuilder = $this->createQueryBuilder("u");

        $queryBuilder->select(
            "c.id as cityId, c.name as cityName, count(u.id) total, sum(case when u.isActive = 1 then 1 else 0 end) validated, sum(case when u.isActive is null then 1 else 0 end) invalidated"
            //"u"
        )
        ->leftJoin('u.housekeeping', 'h')
        ->leftJoin('h.address', 'a')
        ->leftJoin('a.town', 'to')
        ->leftJoin('to.city', 'c')
            /*->setParameter('author', $user->getFavoriteAuthor()->getId())
            ->andWhere('b.publicatedOn IS NOT NULL');*/
            ;   
        $queryBuilder->groupBy("c.id");
        if (!$isTest) {
            //dd(!$isTest);
            $queryBuilder->andWhere('u.isNormal = :normal');
            $queryBuilder->setParameter('normal', true);
        }

        $stats = $queryBuilder->getQuery()->getResult(); 
        return $stats;
        
    }
    public function getStatsInvestigator(bool $isTest): array
    {

        $queryBuilder = $this->createQueryBuilder("u");

        $queryBuilder->select(
            "i.id investId, i.phoneNumber investPhone, i.name instName, i.firstname investFirstname, c.id as cityId, c.name as cityName, count(u.id) total, sum(case when u.isActive = 1 then 1 else 0 end) validated, sum(case when u.isActive is null then 1 else 0 end) invalidated"
            //"u"
        )
        ->leftJoin('u.housekeeping', 'h')//instigator
        ->leftJoin('u.instigator', 'i')//instigator
        ->leftJoin('h.address', 'a')
        ->leftJoin('a.town', 'to')
        ->leftJoin('to.city', 'c')
            /*->setParameter('author', $user->getFavoriteAuthor()->getId())
            ->andWhere('b.publicatedOn IS NOT NULL');*/
            ;   
        $queryBuilder->groupBy("c.id, i.id");
        if (!$isTest) {
            //dd(!$isTest);
            $queryBuilder->andWhere('u.isNormal = :normal');
            $queryBuilder->setParameter('normal', true);
        }

        $stats = $queryBuilder->getQuery()->getResult(); 
        return $stats;
        
    }
    public function getStatsDays(bool $isTest): array
    {

        $queryBuilder = $this->createQueryBuilder("u");

        $queryBuilder->select(
            "DATE_FORMAT(u.createdAt, '%Y-%m-%d') formattedDate, count(u.id) total, sum(case when u.isActive = 1 then 1 else 0 end) validated"
            //"u"
        )
            /*->setParameter('author', $user->getFavoriteAuthor()->getId())
            ->andWhere('b.publicatedOn IS NOT NULL');*/
            ;   
        $queryBuilder->groupBy("formattedDate");
        if (!$isTest) {
            //dd(!$isTest);
            $queryBuilder->andWhere('u.isNormal = :normal');
            $queryBuilder->setParameter('normal', true);
        }

        $stats = $queryBuilder->getQuery()->getResult(); 
        return $stats;
        
    }

    public function getBooksByFavoriteAuthorStatsDay(
        FilterUserDto $filterUserDto = null, 
        int $page = 1, bool $onlyActived = true, 
        bool $isTest=true
    ): array
    {
        $queryBuilder = $this->createQueryBuilder("u");

        $queryBuilder->select(
            "DATE_FORMAT(u.createdAt, '%Y-%m-%d') formattedDate, count(u.id) total, sum(case when u.isActive = 1 then 1 else 0 end) validated"
            //"u"
        )
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
            //dd($isTest);
            if (!$isTest) {
                //dd(!$isTest);
                $queryBuilder->andWhere('u.isNormal = :normal');
                $queryBuilder->setParameter('normal', true);
            }

        if ($onlyActived) {
            $queryBuilder->andWhere('u.isActive = :actived');
            $queryBuilder->setParameter('actived', true);
        }

        if($filterUserDto && $filterUserDto->getSearch()) {

            $search = strtolower($filterUserDto->getSearch()) ;
            $queryBuilder->andWhere('u.id LIKE :id OR lower(u.name) LIKE :name OR lower(u.lastName) LIKE :lastName OR lower(u.firstName) LIKE :firstName OR lower(u.phoneNumber) LIKE :phoneNumber')
                ->setParameter('id', "%" .$search. "%")
                ->setParameter('name', "%" .$search. "%")
                ->setParameter('lastName', "%" .$search. "%")
                ->setParameter('firstName', "%" .$search. "%")
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
        $queryBuilder->groupBy("formattedDate");
        $stats = $queryBuilder->getQuery()->getResult(); 
        return $stats;
        
    }


    function findIfInstigatorIsNull() : array 
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.instigator is null')
            //->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
        
    }

    /**
     * @return Productor[] Returns an array of Productor objects
     */
    public function findByNotValidated()
    {
        return $this->createQueryBuilder('p')
                ->leftJoin('p.housekeeping', 'h')
                ->leftJoin('h.address', 'a')
                ->leftJoin('a.town', 'to')
                ->leftJoin('to.city', 'c')
            ->andWhere('p.isNormal = 1')
            ->andWhere('p.isActive = 0')
            ->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @return Productor[] Returns an array of Productor objects
     */
    function findByInvestigator(string $phone) : array 
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.investigatorId = :val')
            ->andWhere('p.isActive is null')
            ->setParameter('val', $phone)
            ->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        
    }

    /**
     * @return Productor[] Returns an array of Productor objects
     */
    function findByInvestigatorNotIvalid(string $phone) : array 
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.investigatorId = :val')
            ->andWhere('p.isNormal = :isNormal')
            ->setParameter('val', $phone)
            ->setParameter('isNormal', true)
            ->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        
    }

    /**
     * @return Productor[] Returns an array of Productor objects
     */
    function countByInvestigator(DateTime $dateTime = null) : array 
    {
        if(is_null($dateTime)) {
            $dateTime = new DateTime();
        }
        //DateTimeInterface::RFC3339_EXTENDED
        //Y-m-d\TH:i:s
        $startDate = new DateTime($dateTime->format("Y-m-d\T"."00:00:00"));
        $endDate = new DateTime($dateTime->format("Y-m-d\T"."23:59:59"));
        //$dateTime->add;
        
        return $this->createQueryBuilder('p')
            //->andWhere('p.investigatorId = :val')
            ->select('invest.id as id, invest.name as name, invest.firstname as firstname,  invest.lastname as lastname, invest.phoneNumber as phoneNumber, c.name as cityname, count(p.id) total, sum(case when p.isActive = 1 then 1 else 0 end) validated')
            ->andWhere('p.isNormal = :isNormal')
            ->andWhere('p.createdAt between :startDate and :endDate')
            ->join('p.instigator', 'invest')

            ->leftJoin('p.housekeeping', 'h')
            ->leftJoin('h.address', 'a')
            ->leftJoin('a.town', 'to')
            ->leftJoin('to.city', 'c')
            //->setParameter('val', $phone)
            ->setParameter('isNormal', true)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('invest.id, c.id')
            ->orderBy('total')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        
    }

    
}
