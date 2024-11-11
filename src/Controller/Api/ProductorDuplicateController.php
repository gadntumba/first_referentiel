<?php

namespace App\Controller\Api;

use App\Dto\FilterPreloadDto;
use App\Dto\FilterPreloadDUplicateDto;
use App\Entity\Productor;
use App\Entity\ProductorBrut;
use App\Entity\ProductorPreloadDuplicate;
use App\Repository\CityRepository;
use App\Repository\ProductorPreloadDuplicateRepository;
use App\Repository\ProductorPreloadRepository;
use App\Repository\TerritorryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Security\User\OAuthUser;
use App\Services\ManagerGetInstigator;

class ProductorDuplicateController extends AbstractController
{
    public function __construct(
      private EntityManagerInterface $em
    ) 
    {
        
    }

    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/duplicates/all", methods={"GET"}, name="all_productor_preload_duplicate")
     */
    public function allNotConfirm(
      ProductorPreloadDuplicateRepository $repository,
      NormalizerInterface $normalizer,
      ManagerGetInstigator $managerGetInstigator,
      CityRepository $cityRepository,
      TerritorryRepository $territorryRepository,
      Request $req
    ): Response
    {
      $query = $req->query;
      //dd($query->all());
      //dd($this->getUser()->getNormalUsername());
      $phoneNumber = $this->getUser()->getNormalUsername();
      $assingnation = $managerGetInstigator->getAssignationInvestigator($phoneNumber);
      //dd($assingnation);
      $cityName = isset($assingnation["cityName"])?$assingnation["cityName"]:null;
      $territoryName = isset($assingnation["territoryName"])?$assingnation["territoryName"]:null;
      
      $cities = [];
      $territories = [];
      
      if (!$this->isGranted("ROLE_ADMIN") && is_null($cityName) && is_null($territoryName)) {
        return new HttpException(403, "Vous n'etes pas affectez à une ville");
      }
      #dd();
      if ($this->isGranted("ROLE_ADMIN")) {
        
      }else if (!is_null($cityName)) 
      {
        $city = $cityRepository->findOneBy(["name" => $cityName]);
        $cities = (!is_null($city))? [$city->getId()]:[]; 
        #dump($phoneNumber);
        #dd($assingnation);
        
      }else if(!is_null($territoryName)){

        $territory = $territorryRepository->findOneBy(["name" => $territoryName]);
        $territories = (!is_null($territory))? [$territory->getId()]:[]; 
      }

      $arrQuery = $query->all();
      $filter = new FilterPreloadDUplicateDto;
      
      $filter->setSearch(isset($arrQuery['search'])?$arrQuery['search']: null);
      //$filter->setCities(isset($arrQuery['cities'])?$arrQuery['cities']:[]);
      $filter->setCities(isset($arrQuery['cities'])?[...$cities,...$arrQuery['cities']]:$cities);
      //$filter->setTowns(isset($arrQuery['towns'])?[...$territories,...$arrQuery['towns']] :$territories);

      $filter->setDateStart(isset($arrQuery['datestart'])? new \DateTime($arrQuery['datestart']) :null);
      $filter->setDateEnd(isset($arrQuery['dateend'])?new \DateTime($arrQuery['dateend']) :null);
 
      $page = isset($arrQuery['page'])?(int)$arrQuery['page']:1;

      $paginator = $repository->findNoConfirm($filter, $page);
      $iterotor = $paginator->getIterator();
      
      $data = [];
      foreach ($iterotor as $key => $item) {
          array_push($data, $item);
      }
      
      $resp = [
          "data" => $normalizer->normalize($data,null, ["groups" => ["productors:duplicate:read"]]),
          "totalItems" => $paginator->getTotalItems(),
          "lastPage" => $paginator->getLastPage()
      ];

      return new JsonResponse($resp);

    }
    //
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/assignable/all", methods={"GET"}, name="all_productor_assignable")
     */
    public function findByAssignable(
      ProductorPreloadRepository $repository,
      NormalizerInterface $normalizer,
      ManagerGetInstigator $managerGetInstigator,
      CityRepository $cityRepository,
      TerritorryRepository $territorryRepository,
      Request $req
    ): Response
    {
      $query = $req->query;
      //dd($this->getUser()->getNormalUsername());
      $phoneNumber = $this->getUser()->getNormalUsername();
      $assingnation = $managerGetInstigator->getAssignationInvestigator($phoneNumber);
      //dd($assingnation);
      $cityName = isset($assingnation["cityName"])?$assingnation["cityName"]:null;
      $territoryName = isset($assingnation["territoryName"])?$assingnation["territoryName"]:null;
      
      $cities = [];
      $territories = [];

      if (!$this->isGranted("ROLE_ADMIN") && is_null($cityName) && is_null($territoryName)) {
        return new HttpException(403, "Vous n'etes pas affectez à une ville");
      }
      #dd();
      if ($this->isGranted("ROLE_ADMIN")) {
        
      }else if (!is_null($cityName)) 
      {
        $city = $cityRepository->findOneBy(["name" => $cityName]);
        $cities = (!is_null($city))? [$city->getId()]:[]; 
        #dump($phoneNumber);
        #dd($assingnation);
        
      }else if(!is_null($territoryName)){

        $territory = $territorryRepository->findOneBy(["name" => $territoryName]);
        $territories = (!is_null($territory))? [$territory->getId()]:[]; 
      }
      //dd($query->all());
      $arrQuery = $query->all();
      $filter = new FilterPreloadDto;

      $filter->setSearch(isset($arrQuery['search'])?$arrQuery['search']: null);
      $filter->setCities(isset($arrQuery['cities'])?[...$cities,...$arrQuery['cities']]:$cities);
      $filter->setTowns(isset($arrQuery['towns'])?$arrQuery['towns'] : []);
      $filter->setStrutures(isset($arrQuery['structures'])?$arrQuery['structures']:[]);
      $filter->setQuarters(isset($arrQuery['quarters'])?$arrQuery['quarters']:[]);

      //$filter->setInvests(isset($arrQuery['invests'])?$arrQuery['invests']:[]);

      $filter->setDateStart(isset($arrQuery['datestart'])? new \DateTime($arrQuery['datestart']) :null);
      $filter->setDateEnd(isset($arrQuery['dateend'])?new \DateTime($arrQuery['dateend']) :null);
      $page = isset($arrQuery['page'])?(int)$arrQuery['page']:1;

      //dd($filter);

      $paginator = $repository->findByAssignable($filter, $page);

      //dd($data);
      $iterotor = $paginator->getIterator();
        //$all = $this->repository->findBy([],  array('createdAt' => 'DESC'), 30);
        
        $data = [];
        //$res = $paginator->getQuery()->getResult();
        //$dateNumbers = min(count($statsDays), 7);

        //dd($statsDay);
        //dd($stats);


        //dd($all); "read:productor:level_0"
        foreach ($iterotor as $key => $item) {
            //$itemArr = $this->transform($item, true);
            array_push($data, $item);
        }
        
        $resp = [
            "data" => $normalizer->normalize($data,null, ["groups" => ["productors:assignable:read"]]),
            "totalItems" => $paginator->getTotalItems(),
            "lastPage" => $paginator->getLastPage(),
            //"stats" => $stats,
            //"statsDays" => array_slice($statsDays, (-1)*$dateNumbers, $dateNumbers),
        ];

      #$serializedData = $normalizer->normalize($data,null, ["groups" => ["productors:assignable:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($resp);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/duplicates/{id}/set", methods={"POST"}, name="all_productor_preload_duplicate_set")
     */
    public function setDuplicate(
      ProductorPreloadDuplicateRepository $repository,
      ProductorPreloadDuplicate $productorPreloadDuplicate,
      NormalizerInterface $normalizer
    ): Response
    {
      #$data = $repository->findNoConfirm();

      $productorPreloadDuplicate->setConfirmAt(new \DateTime());
      #$productorPreloadDuplicate->setUserConfirm($this->getUser());
      $productorPreloadDuplicate->setUserConfirmIdentifier($this->getUser()->getNormalUsername());
      $this->em->flush();

      $serializedData = $normalizer->normalize($productorPreloadDuplicate,null, ["groups" => ["productors:duplicate:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($serializedData);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/duplicates/{id}/set-no", methods={"POST"}, name="all_productor_preload_duplicate_set_no")
     */
    public function setNotDuplicate(
      ProductorPreloadDuplicateRepository $repository,
      UserRepository $userRepository,
      ProductorPreloadDuplicate $productorPreloadDuplicate,
      NormalizerInterface $normalizer
    ): Response
    {
      #$data = $repository->findNoConfirm();

      #dd($productorPreloadDuplicate);

      $productorPreloadDuplicate->setSetNotDuplicateAt(new \DateTime());
      #$user = $userRepository->findOneBy(["phoneNumber" => $this->getUser()->getNormalUsername()]);
      #dd($this->getUser()->getNormalUsername());

      $productorPreloadDuplicate->setUserConfirmIdentifier($this->getUser()->getNormalUsername());
      $this->em->flush();

      $serializedData = $normalizer->normalize($productorPreloadDuplicate,null, ["groups" => ["productors:duplicate:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($serializedData);

    }
}