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
use App\Entity\EntrepreneurialActivity\Document;
use App\Services\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

/**
 * 
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class ProductorController extends AbstractController
{
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
        CacheManager $imagineCacheManager,
        MultiPartNormalizer $multiPartNormalizer,
        private FileUploader $fileUploader,
        private LoggerInterface $logger
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
     * @Route("/api/productors", methods={"POST"}, name="productor_crate")
     */
    public function create(
        Request $request, 
        ProductorProductor $productorValidator, 
        EntityManagerInterface $em,
        ValidatorValidatorInterface $validator,
        LoggerInterface $logger
    )
    {

        /**
         * @var OAuthUser
         */
        $user =  $this->getUser();
        
        /**
         * @var ProductorProductor
         */
        try {
            $requestData = $this->getRequestParams($request, true);

            //dd($requestData);

            $logger->info('############### Start data json productor #########');
            $logger->info(\json_encode($requestData));
            $logger->info('########## End Data jso productor ##########');
            
            $productorValidator = $this->denormalizer->denormalize(
                $requestData,
                ProductorProductor::class,
                null,
                [AbstractNormalizer::OBJECT_TO_POPULATE => $productorValidator]
            );

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

        try {
            $em->getConnection()->beginTransaction();
            $productorValidator->validate();
            //dd($productorValidator);
            $productor = new Productor();
            // add the identify data
            $productor = $productorValidator->addPersonnalIdentification($productor);
            // add the house keeping
            $productor->setHousekeeping($productorValidator->getHouseKeeping());
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

            //dd($productor);
            //dump($productor);
            $em->flush();

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

    /**
     * @Route("/api/productors/{id}/documents", methods={"POST"}, name="productor.document")
     * 
     */
    public function addDocument(
        EntityManagerInterface $em,
        Productor $productor,
        Request $request
    ) : Response 
    {
        //dd($productor);

        try {

            $requestData = $this->getRequestParams($request, true);

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

            $path = $this->fileUploader->upload($uploadedFile);
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
    public function list()
    {        
        $all = $this->repository->findBy([],  array('createdAt' => 'DESC'), 30);
        
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
        return new JsonResponse($itemArr, 200);
        
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
        if (isset($itemArr['documents']["entrepreneurialActivities"][0]["documents"])) {
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
        }
        //dd($item);
        //

        $itemArr['images'] = $this->multiPartNormalizer->normalize($item, $itemArr['images']);

        $itemArr['photoPath'] = $this->imagineCacheManager->getBrowserPath($item->getIncumbentPhoto(), "pic_producer");

        $uri = Uri::createFromString($itemArr['photoPath']);

        $itemArr['photoPath'] = $this->getParameter("photo_host").$uri->getPath();
        
        $itemArr['photoNormalPath'] = $item->getIncumbentPhoto();        

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


}