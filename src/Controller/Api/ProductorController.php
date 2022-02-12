<?php

namespace App\Controller\Api;

use App\Entity\Productor;
use App\Validators\Productor\Productor as ProductorProductor;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ProductorController extends AbstractController
{
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer; //int


    public function __construct(DenormalizerInterface $denormalizer)
    {
        $this->denormalizer = $denormalizer;
        
    }

    /**
     * 
     * @Route("/api/v1/productors", name="blog_list")
     */
    public function create(Request $request, ProductorProductor $productorValidator)
    {
        $productorValidator = $this->denormalizer->denormalize(
            $this->getRequestParams($request),
            ProductorProductor::class,
            null,
            [AbstractNormalizer::OBJECT_TO_POPULATE => $productorValidator]
        );
        dd($productorValidator);

    }
    /**
     * 
     */
    public function getRequestParams(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(is_null($data)){

            $data = $request->request->all();   

        }else{

        }

        return $data;
        
    }
}