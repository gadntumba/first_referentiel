<?php

namespace App\Controller\Api;

use App\Entity\Productor;
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
        //dd($count); countByAcitivitiesMulti
        //$activities = $this->repository->countByAcitivities();

        $activitiesGreen = $this->repository->countByAcitivitiesMulti(Productor::ACTIVITY_SECTOR_GREE_ECONOMY);
        $activitiesIndus = $this->repository->countByAcitivitiesMulti(Productor::ACTIVITY_SECTOR_INDUSTRY);
        $activitiesService = $this->repository->countByAcitivitiesMulti(Productor::ACTIVITY_SECTOR_SERVICES);
        $activities = [];

        $activitiesGreen = array_pop($activitiesGreen);
        $activitiesGreen["activityType"] = Productor::ACTIVITY_SECTOR_GREE_ECONOMY;
        $activitiesIndus = array_pop($activitiesIndus);
        $activitiesIndus["activityType"] = Productor::ACTIVITY_SECTOR_INDUSTRY;
        $activitiesService = array_pop($activitiesService);
        $activitiesService["activityType"] = Productor::ACTIVITY_SECTOR_SERVICES;

        array_push($activities,$activitiesGreen);
        array_push($activities,$activitiesIndus);
        array_push($activities,$activitiesService);

       // dd($activities);
        //countByAcitivitiesByCitiesMulti

        try {
            //$activitiesByCities = $this->repository->countByAcitivitiesByCities();
            $activitiesCitiesGreen = $this->repository->countByAcitivitiesByCitiesMulti(Productor::ACTIVITY_SECTOR_GREE_ECONOMY);
            $activitiesCitiesIndus = $this->repository->countByAcitivitiesByCitiesMulti(Productor::ACTIVITY_SECTOR_INDUSTRY);
            $activitiesCitiesService = $this->repository->countByAcitivitiesByCitiesMulti(Productor::ACTIVITY_SECTOR_SERVICES);
            $activitiesCities = [];

            //$activitiesCitiesGreen = array_pop($activitiesCitiesGreen);
            foreach ($activitiesCitiesGreen as $key => $activity) {
                //dd($activitiesCitiesGreen);
                $activity["activityType"] = Productor::ACTIVITY_SECTOR_GREE_ECONOMY;
                array_push($activitiesCities,$activity);
            }

            foreach ($activitiesCitiesIndus as $key => $activity) {
                $activity["activityType"] = Productor::ACTIVITY_SECTOR_INDUSTRY;
                array_push($activitiesCities,$activity);
            }
            foreach ($activitiesCitiesService as $key => $activity) {
                $activity["activityType"] = Productor::ACTIVITY_SECTOR_SERVICES;
                array_push($activitiesCities,$activity);
            }

        } catch (\Throwable $th) {
            throw $th;
            //dd($th->getMessage());
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
            "cities" => $activitiesCities,
        ]);

    }


    

    
}
