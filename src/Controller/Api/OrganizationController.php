<?php

namespace App\Controller\Api;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

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
    function all(Request $request) : Response 
    {
        $page = (int) $request->query->get("page", 1);
        if (!is_int($page)) {
          $page = 1;  
        }
        $offset = 30*($page - 1) + 1;
        
        //$page = 
        //findByGroupName

        $data = $this->repository->findByGroupName(30, $offset);
        //dd($data);

        //$data = $this->repository->findBy([], ["name" => "ASC"], 30, $offset);
        $count = $this->repository->count([]);
        
        $res = array_map(
            function(array $item) : array {
                return [
                    "id" => $item["id"] ,
                    "name" => $item["name"] ,
                    "cityId" => $item["cityId"] ,
                    "cityName" => $item["cityName"]  ,
                    "count" => $item["total"] 

                ];
            },
            $data
        );

        return new JsonResponse([
            "data" => $res,
            "count" => $count,
        ]);
        
    }
    /**
     * @Route("/api/organizations/download", methods={"GET","HEAD"}, name="organization_stats_download")
     * 
     */
    function downloadAll(Request $request) : Response 
    { 
        //$data = $this->repository->findBy([], ["name" => "ASC"], 30, $offset);
        $data = $this->repository->findBy([]);

        dd($data);
        //$count = $this->repository->count([]);
        
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

        $res;

        
        $props = [
            "ID",
            "Noms",
            "Ville",
            "Total"
        ];

        $resultArr = [];

        array_push($resultArr, implode(";", $props) );

        foreach ($res as $key => $it) {
            $resIt = implode(";",[
                $it["id"],
                $it["name"],
                $it["cityName"],
                $it["count"]
            ]);

            array_push(
                $resultArr, 
                $resIt
            );                 
        }
        $response =  new StreamedResponse(
            function () use($resultArr) {
                //$writer->save('php://output');

                $csvData = implode("\n", $resultArr);
                
                file_put_contents('php://output', $csvData);
            }
        );

        $slugger = new AsciiSlugger();

        
        $slugProject = $slugger->slug("groups");

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'producer-' . strtoupper($slugProject) . '.csv'
        );


        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Cache-Control', 'max-age=0');


        return $response;

        return new JsonResponse([
            "data" => $res,
            "count" => $count,
        ]);
        
    }

}