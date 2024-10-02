<?php

namespace App\Repository;

use App\Entity\ProductorPreload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function findBySecondaryNotDuplicate()
    {
        //http://127.0.0.1:8000/api/productors/assignable/all
        return $this->createQueryBuilder('e1')
            ->join('App\Entity\ProductorPreloadDuplicate', 'e2', 'WITH', 'e2.secondary = e1')  // Joindre l'entité E2 avec secondary pointant vers E1
            ->where('e2.setNotDuplicateAt is Not NULL')  // Condition sur l'attribut confirm
            //->setParameter('confirm', true)   // Paramètre à true
            ->getQuery()
            ->getResult();
    }
    /**
     * Récupérer les enregistrements de E1 liés à E2 par 'secondary' avec confirm = true
     */
    public function findByMainNotSecondary()
    {
        //http://127.0.0.1:8000/api/productors/assignable/all
        
        // Création du QueryBuilder principal pour la table User
        $qb = $this->createQueryBuilder('p');

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

        // Utilisation de EXISTS avec la sous-requête
        $qb->where($qb->expr()->exists($subQb1->getDQL()));
        // Utilisation de NOT EXISTS avec la sous-requête
        $qb->andWhere($qb->expr()->not($qb->expr()->exists($subQb2->getDQL())));

        return $qb->getQuery()->getResult();
    }
    /**
     * Récupérer les enregistrements de E1 liés à E2 par 'secondary' avec confirm = true
     */
    public function findByAssignable()
    {
        //http://127.0.0.1:8000/api/productors/assignable/all
        /*return $this->createQueryBuilder('e1')
            ->join('App\Entity\ProductorPreloadDuplicate', 'e2', 'WITH', 'e2.secondary = e1')  // Joindre l'entité E2 avec secondary pointant vers E1
            ->where('e2.setNotDuplicateAt is Not NULL')  // Condition sur l'attribut confirm
            //->setParameter('confirm', true)   // Paramètre à true
            ->getQuery()
            ->getResult();*/
    return [... $this->findBySecondaryNotDuplicate(), ... $this->findByMainNotSecondary()];
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
            ->getQuery()
            ->getResult();
    }
    /**
    * @return ProductorPreload[] Returns an array of ProductorPreload objects
    */
    public function findByUserAssignation(string $phoneNumber): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.agentAffect = :phoneNumber')
            ->setParameter('phoneNumber', $phoneNumber)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

}
