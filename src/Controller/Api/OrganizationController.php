<?php

namespace App\Controller\Api;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 
 */
class OrganizationController  extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrganizationRepository $repository
    ) 
    {
        
    }
    

    /**
     * @Route("/api/organizations", methods={"GET","HEAD"}, name="organization_stats_count_sinner")
     * 
     */
    function all() : Response 
    {
        $data = $this->repository->findBy([], ["name" => "ASC"]);
        
        $res = array_map(
            function(Organization $item) : array {
                return [
                    "id" => $item->getId(),
                    "name" => $item->getName(),
                    "cityId" => $item->getCity()?->getId(),
                    "cityName" => $item->getCity()?->getName(),
                    "count" => count($item->getProductors()),

                ];
            },
            $data
        );

        return new JsonResponse([
            "data" => $res
        ]);
        
    }


}