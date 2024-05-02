<?php

namespace App\Controller\Api;

use App\Repository\ProductorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;


class ActivityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductorRepository $repository
    ) 
    {

        
    }
    #[Route(
        '/api/activities/stats', 
        name: 'app_api_activities_stats_', 
        methods:['GET']
    )]
    public function list(): Response 
    {
        $count = $this->repository->count(["isNormal" => true]);
        //$count = count($data);
        //dd($count);
        $activities = $this->repository->countByAcitivities();
        try {
            $activitiesByCities = $this->repository->countByAcitivitiesByCities();
        } catch (\Throwable $th) {
            //throw $th;
            dd($th->getMessage());
        }
        //countByAcitivitiesByCities

        //dd($activitiesByCities);

        foreach ($activities as $key => $activity) 
        {
            $activities[$key]["percentage"] =  ($activities[$key]["total"]/$count)*100;
        }

        //dd($activities);

        return new JsonResponse([
            "data" => $activities,
            "cities" => $activitiesByCities,
        ]);

    }


    

    
}
