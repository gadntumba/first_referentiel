<?php

namespace App\Controller\Api;

use App\Entity\Productor;
use App\Repository\ProductorRepository;
use App\Validators\Exception\Exception;
use App\Validators\Productor\Productor as ProductorProductor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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
     * @Route("/api/v1/productors", methods={"POST"}, name="productor_crate")
     */
    public function create(Request $request, ProductorProductor $productorValidator, EntityManagerInterface $em)
    {
        //dd($this->repository->findAll());
        
        /**
         * @var ProductorProductor
         */
        $productorValidator = $this->denormalizer->denormalize(
            $this->getRequestParams($request),
            ProductorProductor::class,
            null,
            [AbstractNormalizer::OBJECT_TO_POPULATE => $productorValidator]
        );
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

            $em->flush();
            return new JsonResponse(
                [
                    "message" => "Created"
                ],
                201
            );
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
     * @Route("/api/v1/productors", methods={"GET","HEAD"}, name="productor_list")
     * 
     */
    public function list()
    {        
        $all = $this->repository->findAll();
        $data = [];

        //dd($all); "read:productor:level_0"
        foreach ($all as $key => $item) {
            $itemArr = $this->transform($item);
            array_push($data, $itemArr);
        }


        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/api/v1/productors/{id}", methods={"GET","HEAD"}, name="productor_show")
     */
    public function show(Request $request, string $id)
    {
        $productor = $this->repository->find($id);
        //dd($productor);
        $itemArr = $this->transform($productor);
        return new JsonResponse($itemArr, 200);
        
    }

    /**
     * 
     */
    private function transform(Productor $productor)
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
        if (
            method_exists($item, "getHousekeeping") &&
            !is_null($item->getHousekeeping())
        ) {
            $itemArr['housekeeping'] = $this->normalizer->normalize(
                $item->getHousekeeping(), 
                null, 
                [
                    'groups' => ['read:productor:house_keeping']
                ]
                
            );                
        }

        return $itemArr;
    }
}