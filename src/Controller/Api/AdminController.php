<?php

namespace App\Controller\Api;

use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AdminController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private NormalizerInterface $normaliser
    ) 
    {
        
    }

    #[Route(
        '/api/admin/validation/denied/access/cities/{id}/investigators/{investigator}', 
        name: 'app_api_admin_validator_denied_access_city', 
        methods:['POST']
    )]
    public function denied(string $id, string $investigator, CityRepository $cityRepository): Response
    {
        $city = $cityRepository->find($id);

        if (is_null($city)) 
        {
            throw new HttpException(404, 'city not found');
        }

        $arr = $city->getSomeoneCanNotValidator();
        array_push($arr, $investigator);

        $city->setSomeoneCanNotValidator($arr);

        $this->em->flush();

        //dd($this->getUser());
       return new JsonResponse([

       ]);
       
    }


    #[Route(
        '/api/admin/validation/grant/access/cities/{id}/investigators/{investigator}', 
        name: 'app_api_admin_validator_grant_access_city', 
        methods:['POST']
    )]
    public function grant(string $id, string $investigator, CityRepository $cityRepository): Response
    {
        $city = $cityRepository->find($id);

        if (is_null($city)) 
        {
            throw new HttpException(404, 'city not found');
        }

        $arr = $city->getSomeoneCanNotValidator();

        $newArr = array_filter(
            $arr,
            function (string $id) use($investigator) {
                return $id != $investigator;
            }
        );

        //array_push($arr, $investigator);

        $city->setSomeoneCanNotValidator($newArr);

        $this->em->flush();

        //dd($this->getUser());
       return new JsonResponse([

       ]);
    }

}
