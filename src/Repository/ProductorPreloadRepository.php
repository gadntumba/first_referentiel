<?php

namespace App\Repository;

use App\Dto\FilterPreloadDto;
use App\Entity\ProductorPreload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator;
use ApiPlatform\Doctrine\Orm\Paginator as ApiPlatformPaginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductorPreload>
 *
 * @method ProductorPreload|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductorPreload|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductorPreload[]    findAll()
 * @method ProductorPreload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductorPreloadRepository extends ServiceEntityRepository
{
    const PAGINATOR_PER_PAGE = 1000;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductorPreload::class);
    }

//    /**
//     * @return ProductorPreload[] Returns an array of ProductorPreload objects
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

//    public function findOneBySomeField($value): ?ProductorPreload
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    /**
     * Récupérer les enregistrements de E1 liés à E2 par 'secondary' avec confirm = true
     */
    public function findBySecondaryNotDuplicate(FilterPreloadDto $filterPreloadDto, int $page=1)
    {
        $firstResult = ($page -1) * self::PAGINATOR_PER_PAGE;
        //http://127.0.0.1:8000/api/productors/assignable/all
        $queryBuilder = $this->createQueryBuilder('e1')
            ->join('App\Entity\ProductorPreloadDuplicate', 'e2', 'WITH', 'e2.secondary = e1')  // Joindre l'entité E2 avec secondary pointant vers E1
            ->where('e2.setNotDuplicateAt is Not NULL')  // Condition sur l'attribut confirm
            //->setParameter('confirm', true)   // Paramètre à true
            ->leftJoin('e1.city', 'c')
            //->getQuery()
            //->getResult()
            ;
            if ($filterPreloadDto && $filterPreloadDto->getTowns() && count($filterPreloadDto->getTowns()) > 0 ) {
                $queryBuilder->andWhere('e1.town IN (:towns)');
                $queryBuilder->setParameter('towns', $filterPreloadDto->getTowns());
            }

            if ($filterPreloadDto && $filterPreloadDto->getQuarters() && count($filterPreloadDto->getQuarters()) > 0 ) {
                $queryBuilder->andWhere('e1.quarter IN (:quarters)');
                $queryBuilder->setParameter('quarters', $filterPreloadDto->getQuarters());
            }

            if ($filterPreloadDto && $filterPreloadDto->getStrutures() && count($filterPreloadDto->getStrutures()) > 0 ) {
                $queryBuilder->andWhere('e1.structure IN (:structures)');
                $queryBuilder->setParameter('structures', $filterPreloadDto->getStrutures());
            }
    
    
            if ($filterPreloadDto && $filterPreloadDto->getCities() && count($filterPreloadDto->getCities()) > 0 ) {
                //dd($filterPreloadDto->getCities());
                $queryBuilder->andWhere('c is not null and c.id IN (:cities)');
                $queryBuilder->setParameter('cities', $filterPreloadDto->getCities());
            }

            $criteria = Criteria::create()
                ->setFirstResult($firstResult)
                ->setMaxResults(self::PAGINATOR_PER_PAGE);
            $queryBuilder->addCriteria($criteria);
    
            $doctrinePaginator = new Paginator($queryBuilder);
            $paginator = new ApiPlatformPaginator($doctrinePaginator);

            return $paginator;

    }
    /**
     * Récupérer les enregistrements de E1 liés à E2 par 'secondary' avec confirm = true
     */
    public function findByMainNotSecondary(FilterPreloadDto $filterPreloadDto, int $page=1) : ApiPlatformPaginator
    {
        $firstResult = ($page -1) * self::PAGINATOR_PER_PAGE;
        //http://127.0.0.1:8000/api/productors/assignable/all
        
        // Création du QueryBuilder principal pour la table User
        $qb = $this->createQueryBuilder('p');
        $qb->leftJoin('p.cityEntity', 'c');
        // Sous-requête pour vérifier l'existence de commandes pour chaque utilisateur
        $subQb1 = $this->_em->createQueryBuilder();
        $subQb1->select('1')
            ->from('App\Entity\ProductorPreloadDuplicate', 'dm')
            ->innerJoin('dm.secondary', 'dms')
            ->where('dm.main = p.id AND dms.productor IS NULL'); // Association entre Order et User

        // Sous-requête pour vérifier l'existence de commandes pour chaque utilisateur
        $subQb2 = $this->_em->createQueryBuilder();
        
        $subQb2->select('1')
            ->from('App\Entity\ProductorPreloadDuplicate', 'ds')
            ->where('ds.secondary = p.id'); // Association entre Order et User

        $subQb3 = $this->_em->createQueryBuilder();

        $subQb3
            ->select('1')
            ->from('App\Entity\ProductorPreload', 'pnd')
            ->join('App\Entity\ProductorPreloadDuplicate', 'e2', 'WITH', 'e2.secondary = pnd')  // Joindre l'entité E2 avec secondary pointant vers E1
            ->where('e2.setNotDuplicateAt is NOT NULL AND pnd.id = p.id')  // Condition sur l'attribut confirm
            //->setParameter('confirm', true)   // Paramètre à true
            //->leftJoin('e1.city', 'c')
            //->getQuery()
            //->getResult()
            ;

        // Utilisation de EXISTS avec la sous-requête
        $qb->where($qb->expr()->exists($subQb3->getDQL()));
        //$qb->where($qb->expr()->exists($subQb1->getDQL()));
        // Utilisation de NOT EXISTS avec la sous-requête
        $qb->orWhere(
            $qb->expr()->andX(
                $qb->expr()->not($qb->expr()->exists($subQb2->getDQL())), 
                $qb->expr()->exists($subQb1->getDQL())
            ));
        //$qb->andWhere($qb->expr()->not($qb->expr()->exists($subQb2->getDQL())));

        $queryBuilder = $qb;//->getQuery()->getResult();

        if ($filterPreloadDto && $filterPreloadDto->getTowns() && count($filterPreloadDto->getTowns()) > 0 ) {
            $queryBuilder->andWhere('p.town IN (:towns)');
            $queryBuilder->setParameter('towns', $filterPreloadDto->getTowns());
        }

        if ($filterPreloadDto && $filterPreloadDto->getQuarters() && count($filterPreloadDto->getQuarters()) > 0 ) {
            $queryBuilder->andWhere('p.quarter IN (:quarters)');
            $queryBuilder->setParameter('quarters', $filterPreloadDto->getQuarters());
        }

        if ($filterPreloadDto && $filterPreloadDto->getStrutures() && count($filterPreloadDto->getStrutures()) > 0 ) {
            $queryBuilder->andWhere('p.structure IN (:structures)');
            $queryBuilder->setParameter('structures', $filterPreloadDto->getStrutures());
        }


        if ($filterPreloadDto && $filterPreloadDto->getCities() && count($filterPreloadDto->getCities()) > 0 ) {
            //dd($filterPreloadDto->getCities());
            $queryBuilder->andWhere('c is not null and c.id IN (:cities)');
            $queryBuilder->setParameter('cities', $filterPreloadDto->getCities());
        }

        if ($filterPreloadDto->getIsNotAss()) {
        //if (true) {
            $queryBuilder->andWhere('p.affectAt is null');            
        }


        //setIsNotAss
        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::PAGINATOR_PER_PAGE);
        $queryBuilder->addCriteria($criteria);

        $doctrinePaginator = new Paginator($queryBuilder);
        $paginator = new ApiPlatformPaginator($doctrinePaginator);

        return $paginator;
    }
    /**
     * Récupérer les enregistrements de E1 liés à E2 par 'secondary' avec confirm = true
     */
    public function findByAssignable(FilterPreloadDto $filterPreloadDto, int $page=1)
    {
        //http://127.0.0.1:8000/api/productors/assignable/all
        /*return $this->createQueryBuilder('e1')
            ->join('App\Entity\ProductorPreloadDuplicate', 'e2', 'WITH', 'e2.secondary = e1')  // Joindre l'entité E2 avec secondary pointant vers E1
            ->where('e2.setNotDuplicateAt is Not NULL')  // Condition sur l'attribut confirm
            //->setParameter('confirm', true)   // Paramètre à true
            ->getQuery()
            ->getResult();*/
        //return [... $this->findBySecondaryNotDuplicate($filterPreloadDto), ... $this->findByMainNotSecondary($filterPreloadDto)];
        return $this->findByMainNotSecondary($filterPreloadDto, $page);
    }
    /**
     * Récupérer les enregistrements de E1 qui n'ont pas de relation avec E2 via 'main' ou 'secondary'
     * @return ProductorPreload[] Returns an array of ProductorPreload objects
     */
    public function findWithoutE2Relation()
    {
        return $this->createQueryBuilder('e1')
            ->leftJoin('App\Entity\ProductorPreloadDuplicate', 'e2', 'WITH', 'e2.main = e1 OR e2.secondary = e1') // Left Join avec E2
            //->leftJoin('App\Entity\ProductorPreloadDuplicate', 'e2', 'WITH', 'e2.main = e1 OR e2.secondary = e1') // Left Join avec E2
            ->where('e2.id IS NULL')  // Condition pour récupérer les E1 sans relation avec E2
            ->setMaxResults(2000)
            ->getQuery()
            ->getResult();
    }
    /**
    * @return ProductorPreload[] Returns an array of ProductorPreload objects
    */
    public function findByUserAssignation(string $phoneNumber): array
    {

        return $this->createQueryBuilder('p')
            ->andWhere('p.agentAffect = :phoneNumber and p.productor is null')
            ->setParameter('phoneNumber', $phoneNumber)
            ->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return ProductorPreload[] Returns an array of ProductorPreload objects
     */
    public function findByGroupQuarter(): array
    {
        return $this->createQueryBuilder('p')
            //->andWhere('p.exampleField = :val')
            //->setParameter('val', $value)
            ->select('p.quarter, count(p.id) nbr')
            ->groupBy('p.quarter')
            //->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return ProductorPreload[] Returns an array of ProductorPreload objects
     */
    public function findByGroupTowns(): array
    {
        return $this->createQueryBuilder('p')
            //->andWhere('p.exampleField = :val')
            //->setParameter('val', $value)
            ->select('p.town, count(p.id) nbr')
            ->groupBy('p.town')
            //->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return ProductorPreload[] Returns an array of ProductorPreload objects
     */
    public function findByGroupStructures(): array
    {
        return $this->createQueryBuilder('p')
            //->andWhere('p.exampleField = :val')
            //->setParameter('val', $value)
            ->select('p.structure, count(p.id) nbr')
            ->groupBy('p.structure')
            //->orderBy('p.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

}
