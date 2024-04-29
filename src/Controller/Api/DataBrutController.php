<?php

namespace App\Controller\Api;

use App\Entity\MetaDataBrut;
use App\Entity\Organization;
use App\Repository\MetaDataBrutRepository;
use App\Repository\OrganizationRepository;
use App\Services\MetaDataBrutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 
 */
class DataBrutController  extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private MetaDataBrutRepository $metaDataBrutRepository,
        private MetaDataBrutService $service
    ) 
    {
        
    }
    

    /**
     * @Route("/api/metadata", methods={"GET","HEAD"}, name="metadata_all")
     * 
     */
    public function all() : Response 
    {
        $data = $this->metaDataBrutRepository->findBy([], ["name" => "ASC"], 30);
        
        $res = array_map(
            function(MetaDataBrut $item) : array {
                return [
                    "id" => $item->getId(),
                    "fileName" => $item->getFileName(),
                    "cityId" => $item->getSource()
                ];
            },
            $data
        );

        return new JsonResponse([
            "data" => $res
        ]);
        
    }


    /**
     * @Route("/api/metadata", methods={"POST"}, name="metadata_create")
     * 
     */
    public function setMetaData(Request $request) : Response 
    {
        $data = $this->getRequestParams($request);

        $this->metaDataBrutRepository;

        $metaData = $this->service->create($data);;

        /*$metaData->setFileName(isset($data["fileName"])?$data["fileName"]:null);
        $metaData->setThisSchema(isset($data["thisSchema"])?$data["thisSchema"]:null);
        $metaData->setSource(isset($data["source"])?$data["source"]:null);
        $metaData->setCityName(isset($data["cityName"])?$data["cityName"]:null);
        $metaData->setOtherContent(isset($data["otherContent"])?$data["otherContent"]:null);
        $metaData->setOtherContent2(isset($data["otherContent2"])?$data["otherContent2"]:null);

        $metaData->setCheetTitle(isset($data["cheetTitle"])?$data["cheetTitle"]:null);*/

        //$this->em->persist($metaData);

        return new JsonResponse([
            "data" => [
                "id" => $metaData->getId(), 
                "cityName" => $metaData->getCityName(), 
                "cheetTitle" => $metaData->getCheetTitle(), 
                "source" => $metaData->getSource(),
            ]
        ]);
    }

    /**
     * 
     */
    private function getRequestParams(Request $request, bool $addFiles = false)
    {
        $data = json_decode($request->getContent(), true);

        if(is_null($data)){

            $data = $request->request->all();  
            

        }else{

        }

        if ($addFiles) {
            //dd($request->files->all());
            $data = $this->addOtherParams($data,  $request->files->all());
            
        } 

        return $data;
        
    }

}