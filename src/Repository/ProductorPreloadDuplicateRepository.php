<?php

namespace App\Repository;

use App\Dto\FilterPreloadDUplicateDto;
use App\Entity\ProductorPreload;
use App\Entity\ProductorPreloadDuplicate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator;
use ApiPlatform\Doctrine\Orm\Paginator as ApiPlatformPaginator;

/**
 * @extends ServiceEntityRepository<ProductorPreloadDuplicate>
 *
 * @method ProductorPreloadDuplicate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductorPreloadDuplicate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductorPreloadDuplicate[]    findAll()
 * @method ProductorPreloadDuplicate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductorPreloadDuplicateRepository extends ServiceEntityRepository
{
    const PAGINATOR_PER_PAGE=1;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductorPreloadDuplicate::class);
    }

//    /**
//     * @return ProductorPreloadDuplicate[] Returns an array of ProductorPreloadDuplicate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductorPreloadDuplicate
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    /**
     * @return ProductorPreloadDuplicate[] Returns an array of ProductorPreloadDuplicate objects
     */
    public function findDuplicatePossible(ProductorPreload $main, ProductorPreload $secondary): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.main', "m")
            ->innerJoin('p.secondary', "s")
            ->orWhere('m.id = :manId AND s.id = :secondaryId')
            ->orWhere('m.id = :secondaryId AND s.id = :manId')
            ->setParameter('manId', $main->getId())
            ->setParameter('secondaryId', $secondary->getId())
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return ApiPlatformPaginator Returns an array of ProductorPreloadDuplicate objects
     */
    public function findNoConfirm(FilterPreloadDUplicateDto $filter, int $page=1): ApiPlatformPaginator
    {
        $firstResult = ($page -1) * self::PAGINATOR_PER_PAGE;
        //setSetNotDuplicateAt
        $queryBuilder = $this->createQueryBuilder('p')
            ->innerJoin('p.secondary', 's')
            ->innerJoin('s.cityEntity', 'c')
            ->andWhere('p.confirmAt is null and p.setNotDuplicateAt is null')
            ->orderBy('p.similarity', 'DESC')
            //->setMaxResults(2)
            //->getQuery()
            //->getResult()
        ;

        if ($filter && $filter->getCities() && count($filter->getCities()) > 0 ) {
            //dd($filter->getCities());
            $queryBuilder->andWhere('c is not null and c.id IN (:cities)');
            $queryBuilder->setParameter('cities', $filter->getCities());
        }

        $queryBuilder;
        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::PAGINATOR_PER_PAGE);
        $queryBuilder->addCriteria($criteria);

        $doctrinePaginator = new Paginator($queryBuilder);
        $paginator = new ApiPlatformPaginator($doctrinePaginator);

        return $paginator;
    }

}
