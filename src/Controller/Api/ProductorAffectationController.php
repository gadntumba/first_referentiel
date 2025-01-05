<?php

namespace App\Controller\Api;

use App\Dto\FilterPreloadDto;
use App\Entity\Productor;
use App\Entity\ProductorBrut;
use App\Entity\ProductorPreload;
use App\Entity\ProductorPreloadDuplicate;
use App\Repository\CityRepository;
use App\Repository\ProductorPreloadDuplicateRepository;
use App\Repository\ProductorPreloadRepository;
use App\Repository\UserRepository;
use App\Services\ManagerGetInstigator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductorAffectationController extends AbstractController
{
    public function __construct(
      private EntityManagerInterface $em
    ) 
    {
        
    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/affectations/all", methods={"GET"}, name="all_productor_affectation")
     */
    public function findByAssignable(
      ProductorPreloadRepository $repository,
      NormalizerInterface $normalizer,
      Request $req
    ): Response
    {

      $data = $repository->findByUserAssignation($this->getUser()?->getNormalUsername());

      //dd($data);

      $serializedData = $normalizer->normalize($data,null, ["groups" => ["productors:affectations:read", "productors:assignable:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($serializedData);

    }
    /**
     * 
     * @IsGranted("ROLE_ANALYST")
     * @Route("/api/productors/preload/affectations/{id}/set", methods={"POST"}, name="all_productor_preload_affectation_set")
     */
    public function setAssignation(
      ProductorPreloadDuplicateRepository $repository,
      Request $request,
      ProductorPreload $productorPreload,
      NormalizerInterface $normalizer
    ): Response
    {
      $body = $this->getRequestParams($request);

      #dd($this->getUser()->getNormalUsername());

      if (!isset($body["phoneNumber"]) || is_null($body["phoneNumber"]) || empty($body["phoneNumber"])) 
      {
        return new JsonResponse(["message" => "phoneNumber not valid"], 422);
      }

      $phoneNumber = $body["phoneNumber"];

      $productorPreload->setAgentAffect($phoneNumber);
      $productorPreload->setAffectAt(new \DateTime());
      #$productorPreloadDuplicate->setUserConfirm($this->getUser());
      $productorPreload->setAdminDoAffect($this->getUser()->getNormalUsername());
      $this->em->flush();

      $serializedData = $normalizer->normalize($productorPreload,null, ["groups" => ["productors:affectations:read", "productors:assignable:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($serializedData);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/{id}/contacts/set", methods={"POST"}, name="all_productor_preload_contacts_set")
     */
    public function setContactRepport(
      ProductorPreloadDuplicateRepository $repository,
      Request $request,
      ProductorPreload $productorPreload,
      NormalizerInterface $normalizer
    ): Response
    {
      $body = $this->getRequestParams($request);

      $phoneNumber = $this->getUser()->getNormalUsername();

      if ($phoneNumber != $productorPreload->getAgentAffect()) {
        return new JsonResponse(["message" => "Vous n'etes pas authorisé à faire cete commentaire pour cet enregistrement"], 403);
      }

      if (!isset($body["contactRepport"]) || is_null($body["contactRepport"]) || empty($body["contactRepport"])) 
      {
        return new JsonResponse(["message" => "contactRepport not valid"], 422);
      }

      $contactRepport = $body["contactRepport"];

      if (
        !in_array($contactRepport, ProductorPreload::CONTACT_REPPORTS) 
      ) 
      {
        return new JsonResponse(["message" => "contactRepport not exists"], 422);
      }

      if (!isset($body["contactComment"]) || is_null($body["contactComment"]) || empty($body["contactComment"])) 
      {
        $contactComment = null;
      }else {
        $contactComment = $body["contactComment"];
      }

      if (!in_array($contactComment, ProductorPreload::CONTACT_COMMENTS) ) 
      {
        return new JsonResponse(["message" => "contactComment not exists"], 422);

      }

      if ($contactRepport == ProductorPreload::CONTACT_REPPORT_DISPUTED_CASE && is_null($contactComment)) 
      {
        return new JsonResponse(["message" => "contactComment not valide"], 422);

      }

      if (is_null($productorPreload->getContactAt())) {
        $history = $productorPreload->getContactsHistory()??[];
        array_push(
            $history,
            [
                "repport" => $productorPreload->getContactRepport(),
                "comment" => $productorPreload->getContanctComment(),
                "contactAt" => $productorPreload->getContactAt()
            ]
        );
        $productorPreload->setContactsHistory($history);
      }

      $productorPreload->setContactRepport($contactRepport);
      $productorPreload->setContanctComment($contactComment);
      $productorPreload->setContactAt(new \DateTime());
      #$productorPreloadDuplicate->setUserConfirm($this->getUser());
      #$productorPreload->setAdminDoAffect($this->getUser()?->getNormalUsername());
      $this->em->flush();

      $serializedData = $normalizer->normalize($productorPreload,null, ["groups" => ["productors:affectations:read", "productors:assignable:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($serializedData);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/quaters", methods={"GET"}, name="all_productor_preload_quarter")
     */
    public function getAllQuarters(ProductorPreloadRepository $repository): Response 
    {
        $data = $repository->findByGroupQuarter();
        //dd($data);
        return new JsonResponse($data);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/towns", methods={"GET"}, name="all_productor_preload_towns")
     */
    public function getAllTowns(ProductorPreloadRepository $repository): Response 
    {
        $data = $repository->findByGroupTowns();
        //dd($data);
        return new JsonResponse($data);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/structures", methods={"GET"}, name="all_productor_preload_structures")
     */
    public function getAllStructures(
      ProductorPreloadRepository $repository,
      ManagerGetInstigator $managerGetInstigator,
      CityRepository $cityRepository
    ): Response 
    {
        $phoneNumber = $this->getUser()->getNormalUsername();
        $assingnation = $managerGetInstigator->getAssignationInvestigator($phoneNumber);
        $cityName = isset($assingnation["cityName"])?$assingnation["cityName"]:null;

        if (!$this->isGranted("ROLE_ROOT") && is_null($cityName)) {
          return new HttpException(403, "Vous n'etes pas affectez à une ville");
        }
        #dd();
        if ($this->isGranted("ROLE_ROOT")) {
          
        }else  
        {
          $city = $cityRepository->findOneBy(["name" => $cityName]);
          $cities = (!is_null($city))? [$city->getId()]:[]; 
          #dump($phoneNumber);
          #dd($assingnation);
          
        }

        $data = $repository->findByGroupStructures($cities);
        //dd($data);
        return new JsonResponse($data);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/contacts/repports", methods={"GET"}, name="all_productor_preload_contacts_report")
     */
    public function getAllContactRepport( ): Response 
    {
        return new JsonResponse(ProductorPreload::CONTACT_REPPORTS);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/contacts/comments", methods={"GET"}, name="all_productor_preload_contacts_comment")
     */
    public function getAllContactComment( ): Response 
    {
        return new JsonResponse(ProductorPreload::CONTACT_COMMENTS);

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