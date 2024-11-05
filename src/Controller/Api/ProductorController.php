<?php

namespace App\Controller\Api;

use ApiPlatform\Core\Filter\Validator\ValidatorInterface;
use App\Entity\Productor;
use App\Repository\OTRepository;
use App\Repository\ProductorRepository;
use App\Serializer\UnexpectedValueException;
use App\Validators\Exception\Exception;
use App\Validators\Productor\Productor as ProductorProductor;
use App\Validators\Util\Util;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as ValidatorValidatorInterface;
use \Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use League\Uri\Uri;
use Mink67\MultiPartDeserialize\Services\MultiPartNormalizer;
use Mink67\Security\User as Mink67User;
use App\Security\User\OAuthUser;
use Symfony\Component\HttpKernel\Exception\HttpException;
use ApiPlatform\Exception\InvalidArgumentException;
use ApiPlatform\Exception\ItemNotFoundException;
use ApiPlatform\Core\Bridge\Symfony\Routing\IriConverter;
use ApiPlatform\Core\Api\IriConverterInterface;
use App\Dto\FilterUserDto;
use App\Entity\DownloadItemProductor;
use App\Entity\EntrepreneurialActivity;
use App\Entity\EntrepreneurialActivity\Document;
use App\Entity\HouseKeeping;
use App\Entity\Observation;
use App\Entity\ProductorPreload;
use App\Repository\DataBrutRepository;
use App\Repository\DownloadItemProductorRepository;
use App\Repository\EntrepreneurialActivity\DocumentRepository;
use App\Repository\ProductorPreloadRepository;
use App\Services\CopyEntityValuesService;
use App\Services\FileUploader;
use DateTimeImmutable;
use DateTimeInterface;
use Dompdf\Dompdf;
use Imagine\Filter\Basic\Copy;
use Pusher\Pusher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Contracts\HttpClient\HttpClientInterface;


/**
 * 
 */
class ProductorController extends AbstractController
{
    const PIC_ACTIVITY_TYPE="activity";
    const PIC_INCUMBENT= "incumbentPhoto";
    const PIC_PIECE_OF_ID="photoPieceOfIdentification";

    const PIC_TYPE=[self::PIC_ACTIVITY_TYPE, self::PIC_INCUMBENT, self::PIC_PIECE_OF_ID];

    /**
     * @var DenormalizerInterface
     */
    private $denormalizer; //int
    /**
     * @var NormalizerInterface
     */
    private $normalizer; //int
    /**
     * @var ProductorRepository
     */
    private $repository; //int
    private $productor;

    /**
     * @var OTRepository
     */
    private $oTRepository;
    /**
     * @var CacheManager
     */
    private $imagineCacheManager;
    /**
     * @var MultiPartNormalizer
     */
    private $multiPartNormalizer;
    

    public function __construct(
        DenormalizerInterface $denormalizer, 
        ProductorRepository $repository,
        NormalizerInterface $normalizer,
        OTRepository $oTRepository,
        private HttpClientInterface $httpClient,
        CacheManager $imagineCacheManager,
        MultiPartNormalizer $multiPartNormalizer,
        private FileUploader $fileUploader,
        private LoggerInterface $logger,
        private CopyEntityValuesService $copyEntityValuesService
    )
    {
        $this->denormalizer = $denormalizer;
        $this->normalizer = $normalizer;
        $this->repository = $repository;
        $this->oTRepository = $oTRepository;
        $this->imagineCacheManager = $imagineCacheManager;
        $this->multiPartNormalizer = $multiPartNormalizer;
        
    }

    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors", methods={"POST"}, name="productor_crate")
     */
    public function create(
        Request $request, 
        ProductorProductor $productorValidator, 
        EntityManagerInterface $em,
        ProductorPreloadRepository $productorPreloadRepository,
        ValidatorValidatorInterface $validator,
        LoggerInterface $logger
    )
    {
        /**
         * @var OAuthUser
         */
        $user =  $this->getUser();

        if (!$this->isGranted("ROLE_INVESTIGATOR")) {
          throw new HttpException(403, "No access");  
        }
        //dd($user->getRoles());

        /**
         * @var ProductorProductor
         */
        try {
            $requestData = $this->getRequestParams($request, true);
            # Traitement pour le preload

            if (!isset($requestData["preloadId"])) {
                throw new HttpException(422, "preload can't be null");
            }

            $preloadId = $requestData["preloadId"];

            $preload = $productorPreloadRepository->find($preloadId);

            if (is_null($preload)) 
            {
                throw new HttpException(422, "preload not found");                
            }
            $phoneNumberUser = $this->getUser()->getNormalUsername();

            if ($phoneNumberUser != $preload->getAgentAffect()) {
                throw new HttpException(403, "You can't do this record");  
            }
            
            #Fin traiement pour preload


           // dd($requestData);

            //$logger->info('############### Start data json productor #########');
            //$logger->info(\json_encode($requestData));
            //$logger->info('########## End Data jso productor ##########');
            /**
             * @var ProductorProductor
             */
            $productorValidator = $this->denormalizer->denormalize(
                $requestData,
                ProductorProductor::class,
                null,
                [AbstractNormalizer::OBJECT_TO_POPULATE => $productorValidator]
            );

            //dd("OK");

            $phoneNumber = $productorValidator->getPersonnalIdentityData()->getPhone();

        } catch (UnexpectedValueException $th) {
            //throw $th;
            //dd($th);
            $this->logger->error($th->getMessage());
            
            return new JsonResponse(
                [
                    "errors" => $th->getErrors(),
                    "message" => $th->getMessage(),
                    "code" => 422,
                    "status" => 422,
                ],
                422
            );
        }

        //dd($productorValidator);

        try {
            $em->getConnection()->beginTransaction();
            //dd('oke');

            $productor = $this->repository->findOneBy(["phoneNumber" => $phoneNumber]);
            //dd($productor);

            if (is_null($productor)) 
            {
                //dd("ok");
                $productor = new Productor();  

            }elseif($productor->getInvestigatorId() !=  $user->getNormalUsername()) {

                throw new HttpException(422, "PhoneNumber is alrady exists");
                
            }
            $em->initializeObject($productor);
            //dump($productorValidator->getHouseKeeping()->getAddress()->getLine());
            // add the identify data
            // add the house keeping
            if (!is_null($productor->getHousekeeping())) {
                //$noramlHouseKeeping = $em->getRepository(HouseKeeping::class)->find($productor->getHousekeeping()->getId());
                //dump($noramlHouseKeeping);
                //dd('ok');
                $houseKeeping = $this->copyEntityValuesService->copyValues($productor->getHousekeeping(), $productorValidator->getHouseKeeping());
                //dd($houseKeeping);
                $productorValidator->setHousekeeping($houseKeeping) ;
            }

            $productor->setHousekeeping($productorValidator->getHouseKeeping());

            //dd($productorValidator->getHouseKeeping()->getAddress()->getLine());

            $productorValidator->validate();
            //dd($productorValidator);

            $productor = $productorValidator->addPersonnalIdentification($productor);
            // add pieceOfIdentificationData
            $productor = $productorValidator->addPieceOfIdentificationData($productor);//
            /**
             * @var Productor
             */
            $productor = $productorValidator->addActivities($productor);//
            // add other
            $productor->setLatitude($productorValidator->getLatitude());
            $productor->setLongitude($productorValidator->getLongitude());
            $productor->setAltitude($productorValidator->getAltitude());


            $housekeeping = $productor->getHousekeeping();
            $address = $housekeeping->getAddress();

            //dump($housekeeping);
            $this->persistIfNotPersited($address, $em);
            $this->persistIfNotPersited($housekeeping, $em);
            //dd($productor);
            //$em->persist($housekeeping);
            $this->persistIfNotPersited($productor, $em);

            $agriculturals = $productorValidator->getAgriculturals();
            $fichings = $productorValidator->getFichings();
            $stockRaisings = $productorValidator->getStockRaisings();
            $entrepreneurialChips = $productorValidator->getEntrepreneurships();

            foreach ($agriculturals as $key => $agricultural) {
                //dump($agricultural);
               $this->persistIfNotPersited($agricultural, $em);
            }

            foreach ($fichings as $key => $fiching) {
                //dump($fiching);
                $this->persistIfNotPersited($fiching, $em);
            }

            foreach ($stockRaisings as $key => $stockRaising) {
                //dump($stockRaising);
                $this->persistIfNotPersited($stockRaising, $em);
            }
            foreach ($entrepreneurialChips as $key => $entrepreneurialChip) {
                //dump($stockRaising);
                $this->persistIfNotPersited($entrepreneurialChip, $em);
            }

            $errors = $validator->validate($productor);

            if (count($errors) > 0) {
                
                return new JsonResponse(
                    Util::tranformErrorsData($errors),
                    422
                );
            }

            //dd($user->getId());
            
            $productor->setInvestigatorId($user->getNormalUsername());
            $isTest = $this->getParameter("agromwinda_load_mode") == "TEST"? true : false;
            $productor->setIsNormal(!$isTest);
            //$productor->setPro(!$isTest);
            //preload
            //dd($productor);
            //dump($productor);

            $preload->setProductor($productor);

            $em->flush();
            //dd($user->getId());
            $itemArr = $this->transform($productor);

            $em->getConnection()->commit();
            //dd();
            #$em->getConnection()->commit();
            return new JsonResponse($itemArr, 201);
            
        } catch (\Throwable $err) {
            $em->getConnection()->rollBack();
            $this->logger->error($err->getMessage());
            if ($err instanceof Exception) {
                return new JsonResponse(
                    $err->getErrors(),
                    422
                );
                # code...
            }else {
                return new JsonResponse(
                    $err->getMessage(),
                    400
                );
            }

        }
        

    }
    #api/productors/upload/brut
    /**
     * @Route("api/productors/upload/brut", methods={"POST"}, name="productor.upload.brut")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * 
     */
    public function setBrut(
        EntityManagerInterface $em,
         ProductorPreloadRepository $repository,
         ValidatorValidatorInterface $validator,
         
        Request $request
    ) : Response  {
        $requestData = $this->getRequestParams($request, false);
        /**
         * @var ProductorPreload
         */
        $entity = $this->denormalizer->denormalize(
            $requestData,
            ProductorPreload::class,
            null,
            ["groups" => ["write:productor:preload"]]
        );

        $entity->setNormalId();

        $errors = $validator->validate($entity);

        if (count($errors) > 0) {
            return new JsonResponse(["message" => (string) $errors], 400);
        }
        //getNormalId

        $entityExists = $repository->findOneBy(["normalId" => $entity->getNormalId()]);

        if (!is_null($entityExists)) {
            return new JsonResponse( ["message" => "productor alrady exists"], 400);
            
        }

        $em->persist($entity);
        $em->flush();

        $data = $this->normalizer->normalize($entity, null, ["groups" => ["read:productor:preload"]]);

        return new JsonResponse($data, 200);
        
    }

    /**
     * @Route("/api/productors/{id}/documents", methods={"POST"}, name="productor.document")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * 
     */
    public function addDocument(
        EntityManagerInterface $em,
         $id,
         ProductorRepository $productorRepository,
        Request $request
    ) : Response 
    {
        //dd($productor);
        $requestData = $this->getRequestParams($request, true);
        $productor = $productorRepository->find($id);

        if (!$productor) 
        {
            $phoneNumber = isset($requestData["phoneNumber"])?$requestData["phoneNumber"]:null;
            $productor = $productorRepository->findOneBy(["phoneNumber" => $phoneNumber]);
        }

        //dd($productor);

        if (!$productor) {
            throw new HttpException(404, "productor not found");
        }

        try {


            if (!isset($requestData["file"])) 
            {
                throw new HttpException(422, "You must submit file field");
            }

            $uploadedFile = $requestData["file"];
            $entity = new Document();

            /**
             * @var Document
             */
            $entity = $this->denormalizer->denormalize(
                $requestData,
                Document::class,
                null,
                [AbstractNormalizer::OBJECT_TO_POPULATE => $entity]
            );

            $path = $this->fileUploader->uploadGoogle($uploadedFile);
            $activities = $productor->getEntrepreneurialActivities();

            if (!isset($activities[0])) 
            {
                throw new HttpException(400, "Producer invalid");
            }
            $activity = $activities[0];
            //dd($activities[0]);
            //dump($productor);
            //dd($activity->getDocuments()[0]);

            $entity->setPath($path);
            $entity->setActivity($activity);

            $em->persist($entity);            
            $em->flush();

            $data = $this->normalizer->normalize(
                $entity,
                null,
                [
                    "groups" => ["read:document", "read:doc_type"]
                ]
            );

            //dd($data);
            return new JsonResponse($data, 201);
            
        } catch (\Throwable $th) 
        {
            throw  $th;
            //dd($th);
            //throw $th;
        }
        
    }
    /**
     * @Route("/api/productors/{id}/update", methods={"POST"}, name="productor_update")
     */
    function update(
        EntityManagerInterface $em,
         $id,
         ProductorRepository $productorRepository,
        Request $request
    ) : Response 
    {
        $dataChanged = $this->getRequestParams($request, true);
        $productor = $productorRepository->find($id);
        $user = $this->getUser();

        if (!$productor) {
            throw new HttpException(404, "productor not found");
        }

        if(
            $productor->getInvestigatorId() !=  $user->getNormalUsername() &&
            !$this->isGranted("ROLE_VALIDATOR")
        ) 
        {

            throw new HttpException(422, "User can't update this subscriber");
            
        }

        $activities = [...$productor->getEntrepreneurialActivities()->toArray()];
        
        /**
         * @var EntrepreneurialActivity
         */
        $activity = array_pop($activities);

        $activities = $activity->getActivities()??[];
        $taxes = $activity->getTaxes()??[];
        
        $activity->setDocumentType($this->getParam($dataChanged,"documentType"));

        $activities[0] = $this->getParam($dataChanged,"desc");
        //$activity->setDocumentType($this->getParam($dataChanged,"documentType");
        

        $activities[4] = $this->getParam($dataChanged,"legalStatus");
        //["activities"][5] sectorAgroForestry
        //["activities"][6] sectorIndustry
        $activities[15] = $this->getParam($dataChanged,"affiliationStructure");
        $activities[16] = $this->getParam($dataChanged,"turneOverAmount");
        $activities[47] = $this->getParam($dataChanged,"otherContectNames");
        $activities[48] = $this->getParam($dataChanged,"otherContectPhoneNumber");
        $activities[49] = $this->getParam($dataChanged,"otherContectAddress");
        $activities[50] = $this->getParam($dataChanged,"instigatorOpinion");

        $activities[5] = $this->getParam($dataChanged,"sectorAgroForestry");
        $activities[6] = $this->getParam($dataChanged,"sectorIndustry");
        $activities[7] = $this->getParam($dataChanged,"sectorServices");
        $activities[8] = $this->getParam($dataChanged,"sectorGreeEconomy");
        $activities[9] = $this->getParam($dataChanged,"otherActivitySector");
        $activities[10] = $this->getParam($dataChanged,"transformFruitAndVegetableActivity");
        $activities[11] = $this->getParam($dataChanged,"juiceMakerActivity");
        $activities[12] = $this->getParam($dataChanged,"condimengActivity");
        $activities[13] = $this->getParam($dataChanged,"FumageSalaisonSechageActity");
        //$activities[14] = $this->getParam($dataChanged,"otherActity");
        $activities[15] = $this->getParam($dataChanged,"affiliationStructure");
        $activities[16] = $this->getParam($dataChanged,"turneOverAmount");

        //$activities[17] = $this->getParam($dataChanged,"journalierStaff");
        //$activities[18] = $this->getParam($dataChanged,"pernanentStaff");
        //$activities[56] = $this->getParam($dataChanged,"familyStaff");

        $taxes[18] = $this->getParam($dataChanged,"concourFinancing");
        $taxes[19] = $this->getParam($dataChanged,"padepmeFinancing");
        $taxes[20] = $this->getParam($dataChanged,"otherFinancing");
        //$taxes[21] = $this->getParam($dataChanged,"haveCredit");
        $taxes[22] = $this->getParam($dataChanged,"institutCredit");
        $taxes[23] = $this->getParam($dataChanged,"amountCredit");
        $taxes[24] = $this->getParam($dataChanged,"noDificuty");
        $taxes[25] = $this->getParam($dataChanged,"trainningDificuty");
        $taxes[26] = $this->getParam($dataChanged,"financingDificuty");
        $taxes[27] = $this->getParam($dataChanged,"tracaserieDificuty");
        $taxes[28] = $this->getParam($dataChanged,"marketAccessDificuty");
        $taxes[29] = $this->getParam($dataChanged,"productionDificuty");
        $taxes[30] = $this->getParam($dataChanged,"otherDificuty");
        $taxes[31] = $this->getParam($dataChanged,"activityLinkwasteProcessing");
        $taxes[32] = $this->getParam($dataChanged,"activityLinkImprovedStoves");
        $taxes[33] = $this->getParam($dataChanged,"activityLinkRecycling");
        $taxes[34] = $this->getParam($dataChanged,"otherActivityLink");
        $taxes[35] = $this->getParam($dataChanged,"indidualCustomer");
        $taxes[36] = $this->getParam($dataChanged,"supermarketCustomer");
        $taxes[37] = $this->getParam($dataChanged,"businessCustomer");
        $taxes[38] = $this->getParam($dataChanged,"onLineCustomer");
        $taxes[39] = $this->getParam($dataChanged,"dealerCustomer");
        $taxes[40] = $this->getParam($dataChanged,"otherCustomer");
        $taxes[41] = $this->getParam($dataChanged,"visionManyBranches");
        $taxes[42] = $this->getParam($dataChanged,"visionDiversifyClient");
        $taxes[43] = $this->getParam($dataChanged,"visionUsePackaging");
        $taxes[44] = $this->getParam($dataChanged,"visionInprouveTurneOver");
        $taxes[45] = $this->getParam($dataChanged,"visionMakeFactory");
        $taxes[46] = $this->getParam($dataChanged,"visionOther");

        $activity->setActivities($activities);
        $taxes = $activity->setTaxes($taxes);

        if ($this->isGranted("ROLE_VALIDATOR")) {
            
            $productor->setIsActive(true);
            
        }else{
            $productor->setIsActive(false);

        }
        
        $productor->setEditorAgentId($user->getNormalUsername());
        $productor->setEditAt(new DateTime());
        
        $em->flush();

        $data = $this->transform($productor);

        return new JsonResponse($data, 201);
        
    }
    /**
     * @Route("/api/productors/{id}/update_pic", methods={"POST"}, name="productor_update_pic")
     * 
     */
    public function updatePic(
        EntityManagerInterface $em,
         $id,
         ProductorRepository $productorRepository,
         DocumentRepository $documentRepository,
        Request $request) : Response 
    {
        $dataChanged = $this->getRequestParams($request, true);
        $productor = $productorRepository->find($id);
        $user = $this->getUser();

        if (!$productor) {
            throw new HttpException(404, "productor not found");
        }

        if(
            $productor->getInvestigatorId() !=  $user->getNormalUsername() &&
            !$this->isGranted("ROLE_VALIDATOR")
        ) {

            throw new HttpException(422, "User can't update this subscriber");
            
        }

        //const PIC_TYPE=[self::PIC_ACTIVITY_TYPE, self::PIC_INCUMBENT, self::PIC_PIECE_OF_ID];

        $picType = $this->getParam($dataChanged, "type");

        $uploadedFile = $this->getParam($dataChanged, "file", null);

        if (is_null($uploadedFile)) {
            throw new HttpException(422, "File chouldn't empty");
        }

        if (
            $picType == self::PIC_PIECE_OF_ID
        ) 
        {
            $path = $this->fileUploader->uploadGoogle($uploadedFile);
            $productor->setPhotoPieceOfIdentification($path);
            
        }
        elseif ($picType == self::PIC_INCUMBENT) {
            $path = $this->fileUploader->uploadGoogle($uploadedFile);
            $productor->setIncumbentPhoto($path);            
        }
        elseif ($picType == self::PIC_ACTIVITY_TYPE) 
        {
            $idPic = $this->getParam($dataChanged, "idPic", null);
            $doc = $documentRepository->find($idPic);

            if (is_null($doc)) 
            {
                throw new HttpException(404, "Doc not found");                
            }

            $path = $this->fileUploader->uploadGoogle($uploadedFile);
            $doc->setPath($path);
            
        }
        else {
            throw new HttpException(400, "pic type not found");
        }
        
        if ($this->isGranted("ROLE_VALIDATOR")) {
            
            $productor->setIsActive(true);
            
        }else{
            $productor->setIsActive(false);

        }

        //$productor->setIsActive(false);
        
        $em->flush();

        $data = $this->transform($productor);

        return new JsonResponse($data, 201);



        
    }
    /**
     * @Route("/api/productors/{id}/feedback", methods={"POST"}, name="productor_update_pic")
     * 
     */
    public function updateFeedBack(
        EntityManagerInterface $em,
         $id,
         ProductorRepository $productorRepository,
         DocumentRepository $documentRepository, Pusher $pusher,
        Request $request) : Response 
    {
        $dataChanged = $this->getRequestParams($request, true);
        $productor = $productorRepository->find($id);
        //$user = $this->getUser();

        if (!$productor) {
            throw new HttpException(404, "productor not found");
        }

        //const PIC_TYPE=[self::PIC_ACTIVITY_TYPE, self::PIC_INCUMBENT, self::PIC_PIECE_OF_ID];

        if (!$this->isGranted("ROLE_ADMIN") && !$this->isGranted("ROLE_VALIDATOR")) 
        {
            throw new HttpException(403, "ACCESS DENIED");
        }

        $productor->setFeedBack($dataChanged);

        $productor->setIsActive(null);
        $obs = new Observation();
        $obs->setTitle("Invalidation");
        $obs->setContent("pas contenu");

        $this->sendNotification($em, $obs, $productor, $pusher);
        
        $em->flush();

        $data = $this->transform($productor);

        return new JsonResponse($data, 201);
        
    }
    /**
     * @Route("/api/productors/{id}/add/bruts", methods={"GET"}, name="productor_add_brut")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function matchBrutData(
        EntityManagerInterface $em,
         $id,
         ProductorRepository $productorRepository,
         DataBrutRepository $dataBrutRepository,
         Request $request
    ) 
    {
        //$dataChanged = $this->getRequestParams($request, true);

        $idBrut = $request->query->get("id_brut");

        $productor = $productorRepository->find($id);
        $brut = $dataBrutRepository->find($idBrut);
        //$user = $this->getUser();

        //dd($brut);

        if (!$productor) 
        {
            throw new HttpException(404, "productor not found");
        }

        if (!$brut) 
        {
            throw new HttpException(404, "productor not found");
        }
        $brut->setProductor($productor);

        $em->flush();

        return new JsonResponse($this->transform($productor));

    }

    //
    //* 

    /**
     * 
     */
    private function persistIfNotPersited($entity, EntityManagerInterface $em )
    {
        if (
            method_exists($entity, "getId") &&
            is_null($entity->getId())
        ) {

            $em->persist($entity);
            //dump(get_class($entity));
        }else {
            //dump("not");
            //dump(get_class($entity));
            //dd($entity);
        }
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
    /**
     * 
     */
    private function addOtherParams(array $data, array $others) : array {
        foreach ($others as $key => $item) {
            if (is_array($item)) {
                $data[$key] = isset($data[$key])?$data[$key]:[];
                $data[$key] = $this->addOtherParams($data[$key], $item);
            }else {
                $data[$key] = $item;
            }
        }

        return $data;
    }
    /**
     * @Route("/api/productors", methods={"GET","HEAD"}, name="productor_list")
     * 
     */
    public function list(Request $req)
    {        
        $query = $req->query;
        //dd($query->all());
        $arrQuery = $query->all();
        $filter = new FilterUserDto;

        $filter->setSearch(isset($arrQuery['search'])?$arrQuery['search']: null);
        $filter->setProvinces(isset($arrQuery['provinces'])?$arrQuery['provinces']:[]);
        $filter->setCities(isset($arrQuery['cities'])?$arrQuery['cities']:[]);
        $filter->setTerritories(isset($arrQuery['territories'])?$arrQuery['territories']:[]);
        $filter->setTowns(isset($arrQuery['towns'])?$arrQuery['towns']:[]);

        $filter->setInvests(isset($arrQuery['invests'])?$arrQuery['invests']:[]);
        $filter->setActivities(isset($arrQuery['activities'])?$arrQuery['activities']:[]);

        $filter->setSectors(isset($arrQuery['sectors'])?$arrQuery['sectors']:[]);
        $filter->setDateStart(isset($arrQuery['datestart'])? new DateTime($arrQuery['datestart']) :null);
        $filter->setDateEnd(isset($arrQuery['dateend'])?new DateTime($arrQuery['dateend']) :null);

        $filter->setDateValidateStart(isset($arrQuery['datevalidatestart'])? new DateTime($arrQuery['datevalidatestart']) :null);
        $filter->setDateValidateEnd(isset($arrQuery['datevalidateend'])?new DateTime($arrQuery['datevalidateend']) :null);
        //dd($filter);
        //dd($filter);
        //dump($arrQuery['dateend']);
        //dump((new DateTime($arrQuery['dateend']))->format("Y-m-d"));
        //dd();
        $page = isset($arrQuery['page'])?(int)$arrQuery['page']:1;

        $onlyActived = !$this->isGranted("ROLE_ADMIN") && 
            !$this->isGranted("ROLE_ANALYST") && 
            !$this->isGranted("ROLE_VALIDATOR") &&
            !$this->isGranted("ROLE_INVESTIGATOR") 
        ;

        $isInvestigator = $this->isGranted("ROLE_INVESTIGATOR") && !$this->isGranted("ROLE_ADMIN") && 
        !$this->isGranted("ROLE_ANALYST") && 
        !$this->isGranted("ROLE_VOUCHER_COORDINATOR");
        //dd($isInvestigator);

        //dd($onlyActived);
        
        $isTest = $this->getParameter("agromwinda_load_mode") == "TEST"? true : false;

        $paginator = $this->repository->getBooksByFavoriteAuthor(
            $filter, $page, 
            $onlyActived, $isTest, 
            $isInvestigator, $this->getUser()
        );

        //$stats = $this->repository->getBooksByFavoriteAuthorStats($filter, $page, $onlyActived, $isTest);//
        //$statsDays = $this->repository->getBooksByFavoriteAuthorStatsDay($filter, $page, $onlyActived, $isTest);//
        //getBooksByFavoriteAuthorStatsDay
        $iterotor = $paginator->getIterator();
        //$all = $this->repository->findBy([],  array('createdAt' => 'DESC'), 30);
        
        $data = [];
        //$res = $paginator->getQuery()->getResult();
        //$dateNumbers = min(count($statsDays), 7);

        //dd($statsDay);
        //dd($stats);


        //dd($all); "read:productor:level_0"
        foreach ($iterotor as $key => $item) {
            $itemArr = $this->transform($item, true);
            array_push($data, $itemArr);
        }
        
        $resp = [
            "data" => $data,
            "totalItems" => $paginator->getTotalItems(),
            "lastPage" => $paginator->getLastPage(),
            //"stats" => $stats,
            //"statsDays" => array_slice($statsDays, (-1)*$dateNumbers, $dateNumbers),
        ];

        return new JsonResponse($resp, 200);
    }

    /**
     * @Route("/api/productors/others/download", methods={"GET","HEAD"}, name="productor_list_download")
     * 
     */
    public function download(Request $req, DownloadItemProductorRepository $repository) : Response 
    {
        $query = $req->query;
        //dd($query->all());
        $arrQuery = $query->all();

        $cities = isset($arrQuery['cities'])?$arrQuery['cities']:[];
        $page = (int) $query->get("page", 1);
        //dd($cities);

        //$data = $repository->count([]);
        $count_shunk = 250;
        $offset = $count_shunk*($page -1) +1;
        $data = $repository->findAllNormal($cities, $offset, $count_shunk);
        $count = $repository->count([]);
        $allCount = $this->repository->count(["isActive" => true, "isNormal" => true]);
        //dd($data[0]);

        $dataArr = array_map(
            function (DownloadItemProductor $item) : array {
                return [
                    "id" => $item->getId(),
                    "productorId" => $item->getProductorId(),
                    "dataBrut" => $item->getDataBrut(),
                    "cityId" => $item->getCity()?->getId(),
                    "cityName" => $item->getCity()?->getName(),
                    "townId" => $item->getTown()?->getId(),
                    "townName" => $item->getTown()?->getName(),
                    "dataBrut" => $item->getDataBrut(),
                    //dataBrut
                ];  
            },
            $data
        );

        return new JsonResponse([
            "data" => $dataArr,
            "count" => $count,
            "allCount" => $allCount,
            //allCount
        ]);
        
    }
    /**
     * @Route("/api/productors/others/geojson", methods={"GET","HEAD"}, name="productor_list_geojson")
     * 
     */
    public function geojson(Request $req)
    {
        if (!$this->isGranted("ROLE_ADMIN")) 
        {
            throw new HttpException(403, "ACCESS DENIED");
        } 
        $query = $req->query;
        //dd($query->all());
        $arrQuery = $query->all();
        $filter = new FilterUserDto;

        $filter->setSearch(isset($arrQuery['search'])?$arrQuery['search']: null);
        $filter->setProvinces(isset($arrQuery['provinces'])?$arrQuery['provinces']:[]);
        $filter->setCities(isset($arrQuery['cities'])?$arrQuery['cities']:[]);
        $filter->setTerritories(isset($arrQuery['territories'])?$arrQuery['territories']:[]);
        $filter->setTowns(isset($arrQuery['towns'])?$arrQuery['towns']:[]);

        $filter->setInvests(isset($arrQuery['invests'])?$arrQuery['invests']:[]);
        $filter->setActivities(isset($arrQuery['activities'])?$arrQuery['activities']:[]);

        $filter->setSectors(isset($arrQuery['sectors'])?$arrQuery['sectors']:[]);
        $filter->setDateStart(isset($arrQuery['datestart'])? new DateTime($arrQuery['datestart']) :null);
        $filter->setDateEnd(isset($arrQuery['dateend'])?new DateTime($arrQuery['dateend']) :null);
        //dd($filter);
        $page = isset($arrQuery['page'])?(int)$arrQuery['page']:1;

        $onlyActived = !$this->isGranted("ROLE_ADMIN") && 
            !$this->isGranted("ROLE_ANALYST") && 
            !$this->isGranted("ROLE_VALIDATOR") &&
            !$this->isGranted("ROLE_INVESTIGATOR") 
        ;

        $isInvestigator = $this->isGranted("ROLE_INVESTIGATOR") && !$this->isGranted("ROLE_ADMIN") && 
        !$this->isGranted("ROLE_ANALYST") && 
        !$this->isGranted("ROLE_VOUCHER_COORDINATOR");
        //dd($isInvestigator);

        //dd($onlyActived);
        
        $isTest = $this->getParameter("agromwinda_load_mode") == "TEST"? true : false;
        $i = 0;

        $limit = 250;

        $geoData = [
            "type"=> "FeatureCollection",
            "features"=> [
              
            ]
        ];

        $features = [];

            $offset = (($page-1)*$limit)+1;
            //dd($offset);

            $producers = $this->repository->getBooksByGeoJson(
                $filter, $page, 
                $onlyActived, $isTest, 
                $isInvestigator, $this->getUser(), $offset, $limit
            );
            //dd($producers);

            $count = $this->repository->getBooksByGeoJsonCount(
                $filter, $page, 
                $onlyActived, $isTest, 
                $isInvestigator, $this->getUser()
            );

            foreach ($producers as $key => $producer) 
            {
                //dd($producer);
                $feature = [
                    "type"=> "Feature",
                    "geometry"=> [
                      "type"=> "Point",
                      "coordinates"=> [$producer["longitude"], $producer["latitude"]]
                    ],
                    "properties"=> [
                      "names"=> $producer["name"] . " " . $producer["firstName"] . " " . $producer["lastName"],
                      "phone"=> $producer["phoneNumber"],
                      "phone"=> $producer["phoneNumber"],
                      "isActive"=> $producer["isActive"],
                    ]
                ];
                array_push($features, $feature);

                unset($producer);
            }

            unset($producers);
            //dump($i);
            //dump($count);

        $geoData["features"] = $features;

        //dump($i);
        //dd(($i*30)+1);
        $arrCount = array_pop($count);
        $countUnique = $arrCount? array_pop($arrCount): 0;
        $resp = [
            "geoData" => $geoData,
            "count" => $countUnique,
            "countPerPage" => $limit,
            "page" => $page,
            "NbrPage" => ceil($countUnique / $limit) ,
            "NbrPageReal" => $countUnique / $limit,
        ];

        return new JsonResponse($resp, 200);
    }

    /**
     * @Route("/api/stats", methods={"GET","HEAD"}, name="productor_stats")
     * 
     */
    public function stats(Request $req) 
    {
        $isTest = $this->getParameter("agromwinda_load_mode") == "TEST"? true : false;

        $statsAll = $this->repository->getStatsAll($isTest);
        $statsCities = $this->repository->getStatsCities($isTest);
        $statsDays = $this->repository->getStatsDays($isTest);
        $statsInvest = $this->repository->getStatsInvestigator($isTest);//getStatsInvestigator
        //dump($statsAll);
        //dump($statsCities);
        //dump($statsDays);
        //dd();

        return new JsonResponse(
            [
                "statsAll" => $statsAll,
                "statsCities" => $statsCities,
                "statsDays" => $statsDays,
                "statsInvest" => $statsInvest,
            ]
            , 200);
        //$stats = $this->repository->getBooksByFavoriteAuthorStats($filter, $page, $onlyActived, $isTest);//
        //$statsDays = $this->repository->getBooksByFavoriteAuthorStatsDay($filter, $page, $onlyActived, $isTest);//
        
    }
    /**
     * @Route("/api/stats/instigators", methods={"GET","HEAD"}, name="productor_stats_investigator")
     * 
     */
    public function statsInvestigator(Request $req) 
    {
        $isTest = $this->getParameter("agromwinda_load_mode") == "TEST"? true : false;

        $statsInvest = $this->repository->getStatsInvestigator($isTest);//getStatsInvestigator
        //dump($statsAll);
        //dump($statsCities);
        //dump($statsDays);
        //dd();
        $dictData = [];

        foreach ($statsInvest as $key => $item) {
            $id = $item["investPhone"] . $item["cityId"];

            if (!isset($dictData[$id])) 
            {
                $dictData[$id] = [
                    "brutData"=>$item,
                    "countTotal"=>(int)$item["total"],
                    "countValidated"=>(int) $item["validated"]
                ];

            }else 
            {
                $dictData[$id]["countTotal"] = $dictData[$id]["countTotal"] + (int) $item["total"];
                $dictData[$id]["countValidated"] = $dictData[$id]['countValidated'] + (int) $item["validated"];
            }
        }
        

        return new JsonResponse(
            [
                "statsInvest" => $dictData,
            ]
            , 200);
        //$stats = $this->repository->getBooksByFavoriteAuthorStats($filter, $page, $onlyActived, $isTest);//
        //$statsDays = $this->repository->getBooksByFavoriteAuthorStatsDay($filter, $page, $onlyActived, $isTest);//
        
    }
    /**
     * @Route("/api/productors/others/twig/pdf", methods={"GET","HEAD"}, name="productor_list_twig")
     * 
     */
    public function listTwig()
    {        
        $all = $this->repository->findBy([],  array('createdAt' => 'DESC'), 300);

        return $this->render('pdf_generator/all.html.twig', [
            'productors' => $all,
        ]);
        
        $data = [];

        //dd($all); "read:productor:level_0"
        foreach ($all as $key => $item) {
            $itemArr = $this->transform($item, true);
            array_push($data, $itemArr);
        }


        return new JsonResponse($data, 200);
    }

     /**
     * @Route("/api/productors/smartphones/{smartphone}/nui", methods={"GET","HEAD"}, name="productor_smartphone_nui")
     * 
     */
    public function nui(int $smartphone)
    {
        $productors = $this->repository->findBySmartphone($smartphone);

        //dd($productors);

        $nuis = array_map(
            function (Productor $productor)
            {
                $nui = $productor->getNui();

                return $nui;
            },
            $productors
        );


        return new JsonResponse($nuis);
    }
    /**
     * @Route("/api/productors/stats/count", methods={"GET","HEAD"}, name="productor_smartphone_stats_count")
     */
    public function statCount()
    {
        return new JsonResponse([
            "status" => "200",
            "data" => [
                "count" => $this->repository->count([]),
            ],
        ]);
        
    }
    /**
     * @Route("/api/productors/stats/weeks", methods={"GET","HEAD"}, name="productor_smartphone_stats_week")
     */
    public function weekStat()
    {
        $now = new DateTime();

        $res = $this->repository->findWeekStats($now);



        $data = array_reduce(
            $res,
            function ( array $carry , array $item )
            {
                $carry[$item["me_date"]] = (int) $item["nbr"];
                return $carry;
            },
            []
        );

        //dd($data);

        $list = $this->listDaysWeek($now);

        $datesThisWeek = array_map(
            function (DateTime $date) use ($data)
            {
                $dateStr = $date->format("Y-m-d");
                $label = $date->format("d/m");

                $value = [
                    "date" => $dateStr,
                    "label" => $label,
                    "count" => 0
                ];

                if (isset($data[$dateStr])) {
                    $value["count"] = $data[$dateStr];
                }

                return $value;

            },
            $this->listDaysWeek($now)
        );

        $datePrevWeek = clone $now;
        $datePrevWeek = $datePrevWeek->modify("-7 day");

        //dd($datePrevWeek);

        $datesPrevWeek = array_map(
            function (DateTime $date) use ($data)
            {
                $dateStr = $date->format("Y-m-d");
                $label = $date->format("d/m");

                $value = [
                    "date" => $dateStr,
                    "label" => $label,
                    "count" => 0
                ];

                if (isset($data[$dateStr])) {
                    $value["count"] = $data[$dateStr];
                }

                return $value;

            },
            $this->listDaysWeek($datePrevWeek)
        );

        //dd($datesPrevWeek);

        return new JsonResponse([
            "status" => "200",
            "data" => [
                "datesThisWeek" => $datesThisWeek,
                "datesPrevWeek" => $datesPrevWeek
            ],
        ]);

    }

    /**
     * 
     */
    private function weekStatAgricultural()
    {
        $now = new DateTime();

        $res = $this->repository->findWeekStatsAgricultiral($now);

        $data = array_reduce(
            $res,
            function ( array $carry , array $item )
            {
                $carry[$item["me_date"]] = (int) $item["nbr"];
                return $carry;
            },
            []
        );

        //dd($data);

        $datesThisWeek = array_map(
            function (DateTime $date) use ($data)
            {
                $dateStr = $date->format("Y-m-d");
                $label = $date->format("d/m");

                $value = [
                    "date" => $dateStr,
                    "label" => $label,
                    "count" => 0
                ];

                if (isset($data[$dateStr])) {
                    $value["count"] = $data[$dateStr];
                }

                return $value;

            },
            $this->listDaysWeek($now)
        );

        $datePrevWeek = clone $now;
        $datePrevWeek = $datePrevWeek->modify("-7 day");

        //dd($datePrevWeek);

        $datesPrevWeek = array_map(
            function (DateTime $date) use ($data)
            {
                $dateStr = $date->format("Y-m-d");
                $label = $date->format("d/m");

                $value = [
                    "date" => $dateStr,
                    "label" => $label,
                    "count" => 0
                ];

                if (isset($data[$dateStr])) {
                    $value["count"] = $data[$dateStr];
                }

                return $value;

            },
            $this->listDaysWeek($datePrevWeek)
        );

        return compact('datesThisWeek', 'datesPrevWeek');

    }

    /**
     * 
     */
    private function weekStatStockRaisingActivity()
    {
        $now = new DateTime();

        $res = $this->repository->findWeekStatsStockRaisingActivity($now);

        $data = array_reduce(
            $res,
            function ( array $carry , array $item )
            {
                $carry[$item["me_date"]] = (int) $item["nbr"];
                return $carry;
            },
            []
        );

        //dd($data);

        $datesThisWeek = array_map(
            function (DateTime $date) use ($data)
            {
                $dateStr = $date->format("Y-m-d");
                $label = $date->format("d/m");

                $value = [
                    "date" => $dateStr,
                    "label" => $label,
                    "count" => 0
                ];

                if (isset($data[$dateStr])) {
                    $value["count"] = $data[$dateStr];
                }

                return $value;

            },
            $this->listDaysWeek($now)
        );

        $datePrevWeek = clone $now;
        $datePrevWeek = $datePrevWeek->modify("-7 day");

        //dd($datePrevWeek);

        $datesPrevWeek = array_map(
            function (DateTime $date) use ($data)
            {
                $dateStr = $date->format("Y-m-d");
                $label = $date->format("d/m");

                $value = [
                    "date" => $dateStr,
                    "label" => $label,
                    "count" => 0
                ];

                if (isset($data[$dateStr])) {
                    $value["count"] = $data[$dateStr];
                }

                return $value;

            },
            $this->listDaysWeek($datePrevWeek)
        );

        return compact('datesThisWeek', 'datesPrevWeek');

    }

    /**
     * 
     */
    private function weekStatFichingActivity()
    {
        $now = new DateTime();

        $res = $this->repository->findWeekStatsFichingActivity($now);

        $data = array_reduce(
            $res,
            function ( array $carry , array $item )
            {
                $carry[$item["me_date"]] = (int) $item["nbr"];
                return $carry;
            },
            []
        );

        //dd($data);

        $datesThisWeek = array_map(
            function (DateTime $date) use ($data)
            {
                $dateStr = $date->format("Y-m-d");
                $label = $date->format("d/m");

                $value = [
                    "date" => $dateStr,
                    "label" => $label,
                    "count" => 0
                ];

                if (isset($data[$dateStr])) {
                    $value["count"] = $data[$dateStr];
                }

                return $value;

            },
            $this->listDaysWeek($now)
        );

        $datePrevWeek = clone $now;
        $datePrevWeek = $datePrevWeek->modify("-7 day");

        //dd($datePrevWeek);

        $datesPrevWeek = array_map(
            function (DateTime $date) use ($data)
            {
                $dateStr = $date->format("Y-m-d");
                $label = $date->format("d/m");

                $value = [
                    "date" => $dateStr,
                    "label" => $label,
                    "count" => 0
                ];

                if (isset($data[$dateStr])) {
                    $value["count"] = $data[$dateStr];
                }

                return $value;

            },
            $this->listDaysWeek($datePrevWeek)
        );

        return compact('datesThisWeek', 'datesPrevWeek');

    }

    
    /**
     * @Route("/api/productors/stats/weeks/activities", methods={"GET","HEAD"}, name="productor_smartphone_stats_week_activities")
     */
    public function weekStatActivities()
    {

        //dd($datesPrevWeek);

        return new JsonResponse([
            "status" => "200",
            "data" => [
                "weekStatAgricultural" => $this->weekStatAgricultural(),
                "weekStatStockRaisingActivity" => $this->weekStatStockRaisingActivity(),
                "weekStatFichingActivity" => $this->weekStatFichingActivity()
            ],
        ]);

    }
    /**
     * 
     */
    private function listDaysWeek(DateTime $date)
    {
        $numDay = $date->format("N");
        $list = [];

        for ($i=1-$numDay; $i <= 7-$numDay; $i++) { 
            $dateClone = clone $date;
            array_push($list, $dateClone->modify($i . " day"));
        }

        return $list;

    }

    /**
     * @Route("/api/productors/stats/count_farmer", methods={"GET","HEAD"}, name="productor_smartphone_stats_count_farmer")
     * 
     */
    public function countFarmer()
    {
        $data = $this->repository->countAgriculturalActivity();

        return new JsonResponse([
            "data" => $data,
            "code" => 200,
        ]);
    }
    
    /**
     * @Route("/api/productors/stats/count_sinner", methods={"GET","HEAD"}, name="productor_smartphone_stats_count_sinner")
     * 
     */
    public function countSinner()
    {
        $data = $this->repository->countFichingActivity();

        return new JsonResponse([
            "data" => $data,
            "code" => 200,
        ]);
    }

    /**
     * @Route("/api/productors/stats/count_breeder", methods={"GET","HEAD"}, name="productor_smartphone_stats_count_breeder")
     * 
     */
    public function countBreeder()
    {
        $data = $this->repository->countStockRaisingActivity();

        return new JsonResponse([
            "data" => $data,
            "code" => 200,
        ]);
    }

    /**
     * @Route("/api/productors/{id}", methods={"GET","HEAD"}, name="productor_show")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function show(Request $request, string $id)
    {
        $productor = $this->repository->find($id);
        if (is_null($productor)) {
            return new JsonResponse([
                "message" => "Not found"
            ], 200);
        }
        $itemArr = $this->transform($productor);

        //dd($itemArr);
        return new JsonResponse($itemArr, 200);
        
    }

    /**
     * @Route("/api/productors/{id}/pdf", methods={"GET","HEAD"}, name="productor_show_pdf")
     */
    public function showPdf(Request $request, string $id)
    {
        $productor = $this->repository->find($id);
        if (is_null($productor)) {
            return new JsonResponse([
                "message" => "Not found"
            ], 404);
        }

        $itemArr = $this->transform($productor);

        if (isset($itemArr["images"]["photoPieceOfIdentification"])) {
            $itemArr["images"]["photoPieceOfIdentification"] = $this->imageToBase64($itemArr["images"]["photoPieceOfIdentification"]);
        }

        if (isset($itemArr["images"]["incumbentPhoto"])) {
            $itemArr["images"]["incumbentPhoto"] = $this->imageToBase64($itemArr["images"]["incumbentPhoto"]);
        }

        if (isset($itemArr["documents"]["entrepreneurialActivities"])) {
            $typeDocs = $itemArr["documents"]["entrepreneurialActivities"];
        }

        $html =  $this->renderView('pdf_generator/index.html.twig', $itemArr);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );

        return new JsonResponse($itemArr, 200);
        
    }
    /**
     * @Route("/api/productors/{id}/change/preprocessing/ia", methods={"POST"}, name="productor_preprocessing_ia")
     */
    public function preprocessingAi(Request $request, string $id, EntityManagerInterface $em, Pusher $pusher) 
    {
        if (!$this->isGranted("ROLE_ADMIN") && !$this->isGranted("ROLE_VALIDATOR")) 
        {
            throw new HttpException(403, "ACCESS DENIED");
        }

        $productor = $this->repository->find($id);

        if (is_null($productor)) 
        {
            return new JsonResponse([
                "message" => "Not found"
            ], 404);
        }

        $someoneCanNotValidator = $productor->getHousekeeping()?->getAddress()?->getTown()?->getCity()?->getSomeoneCanNotValidator();
        
        if (
            !is_null($someoneCanNotValidator) &&
            in_array($this->getUser()?->getNormalUsername(), $someoneCanNotValidator)
        ) 
        {
            throw new HttpException(403, "access denied");
        }

        $requestData = $this->getRequestParams($request, false);

        //$query = $request->query;
        $desc = isset($requestData["desc"])? $requestData["desc"] : null;
        $activitySector = isset($requestData["activitySector"])? $requestData["activitySector"] : null;

       // dd($desc);

        $productor->setAiDesc($desc);
        $productor->setAiActivitySector($activitySector);

        $em->persist($productor);
        $em->flush();

        return new JsonResponse([
            "data" => $this->transform($productor)
        ]); 

    }
    /**
     * @Route("/api/productors/{id}/change/preprocessing/ia/activity-type", methods={"POST"}, name="productor_preprocessing_ia")
     */
    public function preprocessingAiActType(Request $request, string $id, EntityManagerInterface $em, Pusher $pusher) 
    {
        if (!$this->isGranted("ROLE_ADMIN") && !$this->isGranted("ROLE_VALIDATOR")) 
        {
            throw new HttpException(403, "ACCESS DENIED");
        }

        $productor = $this->repository->find($id);

        if (is_null($productor)) 
        {
            return new JsonResponse([
                "message" => "Not found"
            ], 404);
        }

        $someoneCanNotValidator = $productor->getHousekeeping()?->getAddress()?->getTown()?->getCity()?->getSomeoneCanNotValidator();
        
        if (
            !is_null($someoneCanNotValidator) &&
            in_array($this->getUser()?->getNormalUsername(), $someoneCanNotValidator)
        ) 
        {
            throw new HttpException(403, "access denied");
        }

        $requestData = $this->getRequestParams($request, false);

        //$query = $request->query;
        $type = isset($requestData["type"])? $requestData["type"] : null;

       // dd($desc);
       
        $productor->setAiTypeActivity($type);

        $em->persist($productor);
        $em->flush();

        return new JsonResponse([
            "data" => $this->transform($productor)
        ]); 

    }

    /**
     * @Route("/api/productors/{id}/change/status", methods={"POST"}, name="productor_status")
     */
    public function visibled(Request $request, string $id, EntityManagerInterface $em, Pusher $pusher) 
    {
        if (!$this->isGranted("ROLE_ADMIN") && !$this->isGranted("ROLE_VALIDATOR")) 
        {
            throw new HttpException(403, "ACCESS DENIED");
        }

        $productor = $this->repository->find($id);

        if (is_null($productor)) 
        {
            return new JsonResponse([
                "message" => "Not found"
            ], 404);
        }

        $someoneCanNotValidator = $productor->getHousekeeping()?->getAddress()?->getTown()?->getCity()?->getSomeoneCanNotValidator();
        
        if (
            !is_null($someoneCanNotValidator) &&
            in_array($this->getUser()?->getNormalUsername(), $someoneCanNotValidator)
        ) 
        {
            throw new HttpException(403, "access denied");
        }

        $requestData = $this->getRequestParams($request, false);

        if (!isset($requestData["status"]) || is_null($requestData["status"])) {
            $status = null;

            $obs = new Observation();
            $obs->setTitle("Invalidation");
            $obs->setContent("pas contenu");

            $this->sendNotification($em, $obs, $productor, $pusher);
        }
        elseif ($requestData["status"] === false) {
            $status = false;
        }else {
            $status = true;
        }
        //dd($status);

        $productor->setIsActive($status);
        $productor->setValidatorId($this->getUser()?->getNormalUsername());
        $productor->setValidateAt(new \DateTime());

        $em->flush($productor);
        $itemArr = $this->transform($productor);

        //dd($itemArr);
        return new JsonResponse($itemArr, 200);

    }
    /**
     * @Route("/api/productors/{id}/delete", methods={"POST"}, name="productor_delete")
     */
    public function delete(Request $request, string $id, EntityManagerInterface $em, Pusher $pusher) 
    {
        if (!$this->isGranted("ROLE_ADMIN")) 
        {
            throw new HttpException(403, "ACCESS DENIED");
        }

        $productor = $this->repository->find($id);

        if (is_null($productor)) 
        {
            return new JsonResponse([
                "message" => "Not found"
            ], 404);
        }

        $productor->setIsNormal(false);

        $itemArr = $this->transform($productor);

        $em->flush();
        //dd($itemArr);
        return new JsonResponse($itemArr, 200);

    }

    private function sendNotification(EntityManagerInterface $em, Observation $obs, Productor $productor, Pusher $pusher) : void {
        
        $obs->setProductor($productor);
        $obs->setUserId($this->getUser()?->getNormalUsername());
        $obs->setSendAt(new DateTime());
        
        $em->persist($obs);

        $em->flush();
        //dd('event-'.$productor->getInvestigatorId());
        $result = $pusher->trigger(
            'agrodata', 
            'event-'.$productor->getInvestigatorId(), 
            [
                "idObs"         => $obs->getId(),
                "sendAt"         => $obs->getSendAt()->format(DateTimeInterface::RFC3339_EXTENDED),
                "idProd"         => $productor->getId(),
                "title"         => $obs->getTitle(),
                "content"                   => $obs->getContent(),
                "validatorPhone"         => $this->getUser()?->getNormalUsername(),
                "productor"         => [
                    "names" => $productor->getName() . " " . $productor->getFirstName(). " " . $productor->getLastName(),
                    "phoneNumber" => $productor->getPhoneNumber(),
                ],
            ]
        );
    }

 
    private function imageToBase64($path) {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        //$resp = $this->httpClient->request("GET", $path);
        $data = $this->fileUploader->downloadGoogle($path);
        //$data = $resp->getContent();
        //dd($data);
        //$data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    /**
     * @Route("/api/productors/by-iri", methods={"POST"}, name="productor.iri")
     */
    public function byIri(Request $request, IriConverterInterface $iriConverter)
    {
        //$productor = $this->repository->find($id);

        $requestData = $this->getRequestParams($request, true);
        //dd($requestData);


        if (!isset($requestData["iri"]) || \is_null(isset($requestData["iri"]))) {
            
            throw new HttpException(422, "You must fill in the `iri` of the producer");
            
        }

        $iri = $requestData["iri"];

        $arrIri = explode("/", $iri);
        //dd($arrIri);
        if (
            count($arrIri) != 4 ||
            !str_contains($iri, "/api/producers/") ||
            !((int) $arrIri[3])
        ) {
            throw new HttpException(422, "provided iri is invalid"); 
        }

        $id = (int) $arrIri[3];
        //dd($id);

        //$productor = $iriConverter->getItemFromIri($requestData["iri"]);
        $producer = $this->repository->find($id);

        //dd($producer);

        if (is_null($producer)) {
            return new JsonResponse([
                "message" => "Not found"
            ], 200);
        }

        $itemArr = $this->transform($producer);

        return new JsonResponse($itemArr, 200);
        

        
    }


    /**
     * 
     */
    private function transform(Productor $productor, bool $short=false)
    {
        $item = $productor;


        $itemArr = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:level_0']
            ]
            
        );
        $itemArr['personnalIdentityData'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:personnal_id_data']
            ]
            
        );
        $itemArr['pieceOfIdentificationData'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:piece_of_id_data']
            ]
            
        );
        $itemArr['activityData'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:activities_data']
            ]                
        );  
        $itemArr['housekeeping'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:house_keeping']
            ]                
        )["housekeeping"];

        //""         
        
        //dd($short);
        if (
            method_exists($item, "getHousekeeping") &&
            !is_null($item->getHousekeeping())
            && !$short
        ) {
            $itemArr['housekeeping'] = $this->normalizer->normalize(
                $item->getHousekeeping(), 
                null, 
                [
                    'groups' => ['read:productor:house_keeping']
                ]
                
            );                
        }
        //'timestamp:read'
        $itemArr['timestamp'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['timestamp:read']
            ]
            
        );


        $itemArr['images'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ["read:producer:image"]
            ]
            
        );

        $itemArr['documents'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ["read:producer:document"]
            ]
            
        );

        //dd(count($itemArr['activityData']["entrepreneurialActivities"]));

        if (count($itemArr['activityData']["entrepreneurialActivities"]) > 0) 
        {
            $newData = [...$itemArr['activityData']["entrepreneurialActivities"]];
            
            $freeFieldData = array_pop($newData);
            
            $otherData = [];

            $otherData["desc"] = isset($freeFieldData["activities"][0])? $freeFieldData["activities"][0] :null;
            $otherData["stateMarital"] = isset($freeFieldData["activities"][2])? $freeFieldData["activities"][2] :null;
            $otherData["otherIDCard"] = isset($freeFieldData["activities"][3])? $freeFieldData["activities"][3] :null;
            $otherData["legalStatus"] = isset($freeFieldData["activities"][4])? $freeFieldData["activities"][4] :null;

            $otherData["sectorAgroForestry"] = isset($freeFieldData["activities"][5])? $freeFieldData["activities"][5] : null;
            $otherData["sectorIndustry"] = isset($freeFieldData["activities"][6])? $freeFieldData["activities"][6] : null;
            $otherData["sectorServices"] = isset($freeFieldData["activities"][7])? $freeFieldData["activities"][7] : null;
            $otherData["sectorGreeEconomy"] = isset($freeFieldData["activities"][8])? $freeFieldData["activities"][8] : null;
            $otherData["otherActivitySector"] = isset($freeFieldData["activities"][9])? $freeFieldData["activities"][9] : null;

            $otherData["transformFruitAndVegetableActivity"] = isset($freeFieldData["activities"][10])? $freeFieldData["activities"][10] : null;
            $otherData["juiceMakerActivity"] = isset($freeFieldData["activities"][11])? $freeFieldData["activities"][11] : null;
            $otherData["condimengActivity"] = isset($freeFieldData["activities"][12])? $freeFieldData["activities"][12] : null;
            $otherData["FumageSalaisonSechageActity"] = isset($freeFieldData["activities"][13])? $freeFieldData["activities"][13] : null;
            $otherData["otherActity"] = isset($freeFieldData["activities"][14])? $freeFieldData["activities"][14] : null;

            $otherData["affiliationStructure"] = isset($freeFieldData["activities"][15])? $freeFieldData["activities"][15] : null;
            $otherData["turneOverAmount"] = isset($freeFieldData["activities"][16])? $freeFieldData["activities"][16] : null;

            $otherData["journalierStaff"] = isset($freeFieldData["activities"][17])? $freeFieldData["activities"][17] : null;
            $otherData["pernanentStaff"] = isset($freeFieldData["activities"][55])? $freeFieldData["activities"][55] : null;
            $otherData["familyStaff"] = isset($freeFieldData["activities"][56])? $freeFieldData["activities"][56] : null;

            $otherData["concourFinancing"] = isset($freeFieldData["taxes"][18])? $freeFieldData["taxes"][18] : null;
            $otherData["padepmeFinancing"] = isset($freeFieldData["taxes"][19])? $freeFieldData["taxes"][19] : null;
            $otherData["otherFinancing"] = isset($freeFieldData["taxes"][20])? $freeFieldData["taxes"][20] : null;

            $otherData["haveCredit"] = isset($freeFieldData["taxes"][21])? $freeFieldData["taxes"][21] : null;
            $otherData["institutCredit"] = isset($freeFieldData["taxes"][22])? $freeFieldData["taxes"][22] : null;
            $otherData["amountCredit"] = isset($freeFieldData["taxes"][23])? $freeFieldData["taxes"][23] : null;

            $otherData["noDificuty"] = isset($freeFieldData["taxes"][24])? $freeFieldData["taxes"][24] : null;
            $otherData["trainningDificuty"] = isset($freeFieldData["taxes"][25])? $freeFieldData["taxes"][25] : null;
            $otherData["financingDificuty"] = isset($freeFieldData["taxes"][26])? $freeFieldData["taxes"][26] : null;
            $otherData["tracaserieDificuty"] = isset($freeFieldData["taxes"][27])? $freeFieldData["taxes"][27] : null;
            $otherData["marketAccessDificuty"] = isset($freeFieldData["taxes"][28])? $freeFieldData["taxes"][28] : null;
            $otherData["productionDificuty"] = isset($freeFieldData["taxes"][29])? $freeFieldData["taxes"][29] : null;
            $otherData["otherDificuty"] = isset($freeFieldData["taxes"][30])? $freeFieldData["taxes"][30] : null;

            $otherData["activityLinkwasteProcessing"] = isset($freeFieldData["taxes"][31])? $freeFieldData["taxes"][31] : null;
            $otherData["activityLinkImprovedStoves"] = isset($freeFieldData["taxes"][32])? $freeFieldData["taxes"][32] : null;
            $otherData["activityLinkRecycling"] = isset($freeFieldData["taxes"][33])? $freeFieldData["taxes"][33] : null;
            $otherData["otherActivityLink"] = isset($freeFieldData["taxes"][34])? $freeFieldData["taxes"][34] : null;

            $otherData["indidualCustomer"] = isset($freeFieldData["activities"][35])? $freeFieldData["activities"][35] : null;
            $otherData["supermarketCustomer"] = isset($freeFieldData["activities"][36])? $freeFieldData["activities"][36] : null;
            $otherData["businessCustomer"] = isset($freeFieldData["activities"][37])? $freeFieldData["activities"][37] : null;
            $otherData["onLineCustomer"] = isset($freeFieldData["activities"][38])? $freeFieldData["activities"][38] : null;
            $otherData["dealerCustomer"] = isset($freeFieldData["activities"][39])? $freeFieldData["activities"][39] : null;
            $otherData["otherCustomer"] = isset($freeFieldData["activities"][40])? $freeFieldData["activities"][40] : null;

            $otherData["visionManyBranches"] = isset($freeFieldData["activities"][41])? $freeFieldData["activities"][41] : null;
            $otherData["visionDiversifyClient"] = isset($freeFieldData["activities"][42])? $freeFieldData["activities"][42] : null;
            $otherData["visionUsePackaging"] = isset($freeFieldData["activities"][43])? $freeFieldData["activities"][43] : null;
            $otherData["visionInprouveTurneOver"] = isset($freeFieldData["activities"][44])? $freeFieldData["activities"][44] : null;
            $otherData["visionMakeFactory"] = isset($freeFieldData["activities"][45])? $freeFieldData["activities"][45] : null;
            $otherData["visionOther"] = isset($freeFieldData["activities"][46])? $freeFieldData["activities"][46] : null;


            $otherData["otherContectNames"] = isset($freeFieldData["activities"][47])? $freeFieldData["activities"][47] : null;
            $otherData["otherContectPhoneNumber"] = isset($freeFieldData["activities"][48])? $freeFieldData["activities"][48] : null;
            $otherData["otherContectAddress"] = isset($freeFieldData["activities"][49])? $freeFieldData["activities"][49] : null;

            $otherData["instigatorOpinion"] = isset($freeFieldData["activities"][50])? $freeFieldData["activities"][50] : null;

            $itemArr['activityData']["entrepreneurialActivities"][0]["otherData"] = $otherData;
        }
        /*if (isset($itemArr['documents']["entrepreneurialActivities"][0]["documents"])) {
            $documents = $itemArr['documents']["entrepreneurialActivities"][0]["documents"];

            $imagineCacheManager = $this->imagineCacheManager;

            $documents = array_map(
                function (array $doc) use($imagineCacheManager) {
                    $pathKey = "path";
                    $doc[$pathKey] = $imagineCacheManager->getBrowserPath($doc[$pathKey], "pic_producer");
                    return $doc;
                },
                $documents
            );
            //dd($documents);
            $itemArr['documents'] = $documents;
        }else {
            $itemArr['documents'] = [];
        }*/
        //dd($item);
        //

        //$itemArr['images'] = $this->multiPartNormalizer->normalize($item, $itemArr['images']);

        //$itemArr['photoPath'] = $this->imagineCacheManager->getBrowserPath($item->getIncumbentPhoto(), "pic_producer");

        //$uri = Uri::createFromString($itemArr['photoPath']);

        //$itemArr['photoPath'] = $this->getParameter("photo_host").$uri->getPath();
        
        $itemArr['photoPath'] = $item->getIncumbentPhoto();
        
        $itemArr['photoNormalPath'] = $item->getIncumbentPhoto();  
        
        //$taxes = $productor->;

        return $itemArr;
    }

    /**
     * @Route("/api/productors/{imei}/nui", methods={"GET","HEAD"}, name="nuibyIMEI")
     */
    public function nuibyIMEI(string $imei)
    {
        $productors = $this->repository->findByImei($imei);

        //dd($productors);

        $nuis = array_map(
            function (Productor $productor)
            {
                $nui = $productor->getNui();

                return $nui;
            },
            $productors
        );


        return new JsonResponse($nuis);
    }

    /**
     * @Route("/api/ots/{id}/productors", methods="GET", name="productor_list_ot")
     * 
     */
    public function list_ot($id)
    {

        $all = $this->repository->findBy();
        
        $data = [];

        
        foreach ($all as $key => $item) {
            $itemArr = $this->transform($item, true);
            array_push($data, $itemArr);
        }


        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/api/productors/{id}/add/obs", methods="POST", name="productor_add_obs")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    function postObservation($id, EntityManagerInterface $em, Request $request, Pusher $pusher) : Response 
    {
        $user = $this->getUser();
        //dd($user->getNormalUsername());
        $requestData = $this->getRequestParams($request);
        /**
         * @var Productor
         */
        $productor = $em->getRepository(Productor::class)->find($id);

        //dd($productor->getInvestigatorId());

        if (is_null($productor)) {
            throw new HttpException(404, "Productor not found");
        }

        $obs = new Observation();
        $obs->setTitle(isset($requestData["title"])?$requestData["title"]: "");
        $obs->setContent(isset($requestData["content"])?$requestData["content"]: "pas contenu");
        //$obs->setUserId($productor->getInvestigatorId());
        
        $this->sendNotification($em, $obs, $productor, $pusher);

        //dd($result);
        

        return new JsonResponse([
            "message" => "Ok",
            "data" =>  [
                "idObs"         => $obs->getId(),
                "sendAt"         => $obs->getSendAt()->format(DateTimeInterface::RFC3339_EXTENDED),
                "idProd"         => $productor->getId(),
                "title"         => $obs->getTitle(),
                "content"                   => $obs->getContent(),
                "validatorPhone"         => $this->getUser()?->getNormalUsername(),
                "productor"         => [
                    "names" => $productor->getName() . " " . $productor->getFirstName(). " " . $productor->getLastName(),
                    "phoneNumber" => $productor->getPhoneNumber(),
                ],
            ]

        ], 201);

    }

    /**
     * @Route("/api/productors/{id}/add/obs", methods="GET", name="productor_get_obs")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    function getObservations($id, EntityManagerInterface $em, Request $request, NormalizerInterface $normalizer) : Response 
    {
        //$user = $this->getUser();

        $productor = $em->getRepository(Productor::class)->find($id);

        if (is_null($productor)) {
            throw new HttpException(404, "Productor not found");
        }
        /**
         * @var Observation[]
         */
        $data = $em->getRepository(Observation::class)->findBy(["productor" => $productor]);
        //dd($data);
        $dataArr = array_map(
            function (Observation $item)  {
                return [
                    "id" => $item->getId(),
                    "title" => $item->getTitle(),
                    "content" => $item->getContent(),
                    "askAt" => $item->getAskAt(),
                    "productor" => [
                        "id" => $item->getProductor()?->getId(),
                        "name" => $item->getProductor()?->getName(),
                        "firstname" => $item->getProductor()?->getFirstName(),
                        "lastname" => $item->getProductor()?->getLastName()
                    ],
                    "validator" => $item->getUserId()
                ];
            },
            $data,
        );

        //$dataArr = $this->normalizer->normalize($data, null, ['groups' => ['read:observ'] ]);
        
        return new JsonResponse(["data" => $dataArr, "code" => 201], 201);

    }
    
    /**
     * @Route("/api/productors/obs/{id}/ask", methods="GET", name="productor_ask_obs")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    function getAskObservation($id, EntityManagerInterface $em, Request $request, NormalizerInterface $normalizer) : Response 
    {
        //$user = $this->getUser();
        /**
         *@var Observation 
         */
        $observation = $em->getRepository(Observation::class)->find($id);

        if (is_null($observation)) {
            throw new HttpException(404, "observation not found");
        }


        $observation->setAskAt(new \DateTime());

        //$data = $em->getRepository(Observation::class)->findBy(["productor" => $productor]);
        //dd($data);
        //$dataArr = $normalizer->normalize($data);


        //$em->persist($obs);

        $em->flush();

        $data = [
            "id" => $observation->getId(),
            "title" => $observation->getTitle(),
            "content" => $observation->getContent(),
            "askAt" => $observation->getAskAt(),

            "productor" => [
                "id" => $observation->getProductor()?->getId(),
                "name" => $observation->getProductor()?->getName(),
                "firstname" => $observation->getProductor()?->getFirstName(),
                "lastname" => $observation->getProductor()?->getLastName()
            ],
            
            "validator" => $observation->getUserId()
        ];
        
        return new JsonResponse(["data" => $data, "code" => 201], 201);

    }
    /**
     * @Route("/api/productors/{phone}/invalid-productors", methods={"GET"}, name="productor_update_invalid_productor_instigator")
     * 
     */
    public function getInvalide(string $phone) : Response  
    {
        $data = $this->repository->findByInvestigator($phone);
        $me = $this;
        $jsonData = array_map(
            function ($item) use($me) {
                return $me->transform($item);
            },
            $data
        );

        return new JsonResponse( $jsonData, 200);
        
    }
    /**
     * @Route("/api/productors/{phone}/invalid-not-productors", methods={"GET"}, name="productor_update_not_invalid_productor_instigator")
     * 
     */
    public function getNotInvalide(string $phone) : Response  
    {
        //dd($phone);
        $data = $this->repository->findByInvestigatorNotIvalid($phone);

        //dd($data);
        $me = $this;
        $csvArrData = array_map(
            function ($item) use($me) {
                $dataTransform = $me->transform($item);
                $otherData = isset($dataTransform['activityData']["entrepreneurialActivities"][0]["otherData"])?$dataTransform['activityData']["entrepreneurialActivities"][0]["otherData"]:null ;
                
                if (count($dataTransform['activityData']["entrepreneurialActivities"]) > 0) 
                {
                    $newData = [...$dataTransform['activityData']["entrepreneurialActivities"]];
                    
                    $freeFieldData = array_pop($newData);
                    //subscriber?.activityData?.entrepreneurialActivities[0]?.town
                    if ($freeFieldData) {
                        
                        $activityTownName = isset($freeFieldData["town"])? $freeFieldData["town"]["name"] : "";
                        $activityAddressLine = $freeFieldData["addressLine"];
                        //dd($freeFieldData);
                       $desc = $freeFieldData["activities"]? $freeFieldData["activities"][0] : "";
                        # code...
                    }
                }else {
                    $desc = "";
                    $activityTownName = "";
                    $activityAddressLine = "";
                }

                $line = '"'.$dataTransform["personnalIdentityData"]["firstName"].'";' .
                        '"'.$dataTransform["personnalIdentityData"]["name"].'";' .
                        '"'.$dataTransform["personnalIdentityData"]["lastName"].'";' .
                        '"'.$dataTransform["personnalIdentityData"]["phoneNumber"].'";' .
                        '"'.$desc.'";'.

                        '"'.$dataTransform["housekeeping"]["address"]["town"]["city"]["name"].'";' .
                        '"'.$dataTransform["housekeeping"]["address"]["town"]["name"].'";' .
                        '"'.$dataTransform["housekeeping"]["address"]["line"].'";' .

                        '"'.$activityTownName.'";' .
                        '"'.$activityAddressLine.'";' .

                        ""
                ;

                if ($otherData) {
                    $line = $line .
                        '"'.$otherData["sectorAgroForestry"].'";' .

                        '"'.$otherData["sectorAgroForestry"].'";' .
                        '"'.$otherData["sectorIndustry"].'";' .
                        '"'.$otherData["sectorServices"].'";' .
                        '"'.$otherData["sectorGreeEconomy"].'";' .
                        '"'.$otherData["otherActivitySector"].'";' .

                        '"'.$otherData["otherContectPhoneNumber"].'";' .
                        
                        "";
                }else {
                    $line = $line .
                        '";' .
                        '";' .
                        '";' .
                        '";' .
                        '";' .
                        '";' .
                        "";
                }

                return $line;

            },
            $data
        );

        $firstLine = '"'.'Pré nom'.'";' .
            '"'.'Nom'.'";' .
            '"'.'Post nom'.'";' .
            '"'.'Numero 1'.'";' .
            '"'.'Description'.'";'.

            '"'.'Ville'.'";' .
            '"'.'Commune '.'";' .
            '"'.'Adresse'.'";' .

            '"'.'Commune Activité'.'";' .
            '"'.'Adresse Activité'.'";' .
            '"'.'secteur Agro foresterie'.'";' .

            '"'.'secteur Agro foresterie'.'";' .
            '"'.'secteur industrie'.'";' .
            '"'.'Services'.'";' .
            '"'.'Aconomie verte'.'";' .
            '"'. 'autres'.'";' .

            '"'.'Numéro 2'.'";' 
            ;
        $csvArrData = [$firstLine, ...$csvArrData];

        $response =  new StreamedResponse(
            function () use($csvArrData) {
                //$writer->save('php://output');

                $csvData = implode("\n", $csvArrData);
                
                file_put_contents('php://output', $csvData);
            }
        );

        $slugger = new AsciiSlugger();
        
        $slugProject = $slugger->slug($phone);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'producer-' . strtoupper($slugProject) . '.csv'
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Cache-Control', 'max-age=0');


        return $response;
        
    }
    /**
     * @Route("/api/productors/stats/by/investigator/day", methods={"GET"}, name="productor_update_day_by_instigator")
     * 
     */
    public function getStatsByInvestigarAndDay(Request $req) : Response 
    {
        $date = $req->query->get("date", null);
        if (is_null($date)) {
            $dateTime = new DateTime();
        }else {
            $dateTime = new DateTime($date);
        }
        $data = $this->repository->countByInvestigator($dateTime);

        return new JsonResponse($data);

    }
    /**
     * @Route("/api/productors/stats/by/investigator/period", methods={"GET"}, name="productor_update_period_by_instigator")
     * 
     */
    public function getStatsByInvestigarAndPeriod(Request $req) : Response 
    {
        $dateend = $req->query->get("dateend", null);
        $datestart = $req->query->get("datestart", null);

        if (is_null($dateend)) 
        {
            $dateTimeend = new DateTime();
        }else 
        {
            $dateTimeend = new DateTime($dateend);
        }
        if (is_null($datestart)) 
        {
            $datestartTime = new DateTime();
        }else 
        {
            $datestartTime = new DateTime($datestart);
        }

        //dump($dateTimeend);
        //dd($datestartTime);

        $data = $this->repository->countByInvestigatorPeriod($datestartTime, $dateTimeend);

        return new JsonResponse($data);

    }
    /**
     * @Route("/api/productors/stats/by/groups", methods={"GET"}, name="productor_update_by_group")
     * 
     */
    function getByGroup() : Response 
    {
        $count = $this->repository->count([]);
        $normalCount = round($count/1000)*1000;
        $merge = [];
        //$data = $this->repository->findBy([], null, 1000, 1000+1);
        //dump(count($data));
        //dd();

        for ($i=0; $i < $normalCount; $i+=100)
        {
            //dump($i+1);
            $data = $this->repository->findBy([], null, 30, $i+1);
            //dump(count($data));
            //continue;
    
            foreach ($data as $key => $item) 
            {
                $arr = $item->getEntrepreneurialActivities()?->toArray();
                //dump($item->getName());
                
                
                /**
                 * @var EntrepreneurialActivity
                 */
                $act = array_pop($arr);

    
                if(!is_null($act)) 
                {
                    //dump($act->getName());
                    //dd($act->getName());
                    $act->getName();

                    $myKey = $act->getActivities();
                    
                    if (isset($myKey["15"])) 
                    {
                        if (!isset($merge[$myKey["15"]])) {
                            $merge[$myKey["15"]] = [
                                "name" => $myKey["15"],
                                "count" => 0,
                            ];
    
                        }
    
                        $count = $merge[$myKey["15"]]['count'];
    
                        $merge[$myKey["15"]]['count'] = $count + 1;
                        //dump($myKey["15"]);
                        //dump($merge[$myKey["15"]]['count']);
                        
                    }
    
                }
    
    
            }
            
        }

        //dd();

        return new JsonResponse($merge, 200);
    }


    #[Route('/api/productors/photo/show/{filename}', name: 'productor_photo', methods:["GET"])]
    public function getFile(
        Request $request, 
        $filename, 
        FileUploader $fileUploader
    ): Response
    {
        $prefix = "https://storage.cloud.google.com/agromwinda_platform/";
        
        $path = $prefix . $filename;

        //$data = $fileUploader->downloadGoogle($path);
        $data = $fileUploader->downloadStreamGoogle($path);

        $type = pathinfo($path, PATHINFO_EXTENSION);
        //$resp = $this->httpClient->request("GET", $path);
        //$data = $resp->getContent();
        //dd($data);
        //$data = file_get_contents($path);
        //$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        
        //$user->setPhoto($link);

        //$user

        return new Response($data, 200, ["Content-type" => "image/" . $type]);

    }

    //private
    function getParam(array $arr, string $key, $default=null) {
        return isset($arr[$key])?$arr[$key]: $default;
    }
    //end private


}