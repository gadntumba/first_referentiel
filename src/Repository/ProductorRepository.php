<?php

namespace App\Repository;
use App\Entity\AgriculturalActivity;
use App\Entity\FichingActivity;
use App\Entity\FichingActivityType;
use App\Entity\Productor;
use App\Entity\StockRaisingActivity;
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
     * 
     */
    public function customFindAll($limit = 30)
    {
        return $this->createQueryBuilder('p')
            ->join('p.levelStudy', "l")
            ->orderBy('p.name', 'DESC')
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
    * @return int count a productor agricultor
    */
    public function countAgriculturalActivity()
    {
        $em = $this->getEntityManager();


        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(Productor::class)->getTableName();
        $actityTableName = $em->getClassMetadata(AgriculturalActivity::class)->getTableName();

        $sql = "SELECT count(p.id) nbr FROM $tableName p WHERE EXISTS (SELECT act.id FROM $actityTableName act);
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

        $sql = "SELECT count(p.id) nbr FROM $tableName p WHERE EXISTS (SELECT act.id FROM $actityTableName act);
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

        $sql = "SELECT count(p.id) nbr FROM $tableName p WHERE EXISTS (SELECT act.id FROM $actityTableName act);
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
