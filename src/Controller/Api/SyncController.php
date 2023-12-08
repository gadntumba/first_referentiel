<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SyncController extends AbstractController
{
    /**
     * 
     */
    public function __construct(private NormalizerInterface $normaliser) {
    }
    #[Route('/api/sync', name: 'app_api_sync_')]
    public function index(): Response
    {
        //dd($this->getUser());
       return new JsonResponse([

       ]);
    }
}
