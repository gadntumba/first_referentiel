<?php

namespace App\Controller\Api;

use ApiPlatform\Core\Filter\Validator\ValidatorInterface;
use App\Entity\Productor;
use App\Repository\ProductorRepository;
use App\Serializer\UnexpectedValueException;
use App\Validators\Exception\Exception;
use App\Validators\Productor\Productor as ProductorProductor;
use App\Validators\Util\Util;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as ValidatorValidatorInterface;

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

    public function __construct(
        DenormalizerInterface $denormalizer, 
        ProductorRepository $repository,
        NormalizerInterface $normalizer
    )
    {
        $this->denormalizer = $denormalizer;
        $this->normalizer = $normalizer;
        $this->repository = $repository;
        
    }

    /**
     * @Route("/api/productors", methods={"POST"}, name="productor_crate")
     */
    public function create(
        Request $request, 
        ProductorProductor $productorValidator, 
        EntityManagerInterface $em,
        ValidatorValidatorInterface $validator
    )
    {
        //dd($this->repository->findAll());
        
        /**
         * @var ProductorProductor
         */
        try {
            
            $productorValidator = $this->denormalizer->denormalize(
                $this->getRequestParams($request),
                ProductorProductor::class,
                null,
                [AbstractNormalizer::OBJECT_TO_POPULATE => $productorValidator]
            );
        } catch (UnexpectedValueException $th) {
            //throw $th;
            //dd($th);
            
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
            $productorValidator->validate();
            $productor = new Productor();
            // add the identify data
            $productor = $productorValidator->addPersonnalIdentification($productor);
            // add the house keeping
            $productor->setHousekeeping($productorValidator->getHouseKeeping());
            // add pieceOfIdentificationData
            $productor = $productorValidator->addPieceOfIdentificationData($productor);//
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
            $this->persistIfNotPersited($productor, $em);

            $agriculturals = $productorValidator->getAgriculturals();
            $fichings = $productorValidator->getFichings();
            $stockRaisings = $productorValidator->getStockRaisings();

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



            //$this->persistIfNotPersited($productor, $em);
            //dd("ok");

            $errors = $validator->validate($productor);

            if (count($errors) > 0) {
                
                return new JsonResponse(
                    Util::tranformErrorsData($errors),
                    422
                );
            }


            $em->flush();

            $itemArr = $this->transform($productor);
            return new JsonResponse($itemArr, 201);
            
        } catch (Exception $err) {

            return new JsonResponse(
                $err->getErrors(),
                422
            );

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
    private function getRequestParams(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(is_null($data)){

            $data = $request->request->all();   

        }else{

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
    public function list_ot()
    {        
        $all = $this->repository->findBy([],  array('createdAt' => 'DESC'), 30);
        
        $data = [];

        
        foreach ($all as $key => $item) {
            $itemArr = $this->transform($item, true);
            array_push($data, $itemArr);
        }


        return new JsonResponse($data, 200);
    }

    /**
     *@Route(path="/api/ots/{id}/productors/count",name="ots.{id}.productors.count", methods="GET")
     */
    public function countProductor($id){
        $productor= $this->productor->findAll();
        return  new  JsonResponse([
            "nbre"=>count($productor)
        ]);
       }


}