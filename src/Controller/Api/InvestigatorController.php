<?php

namespace App\Controller\Api;

use App\Services\ManagerGetInstigator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class InvestigatorController extends AbstractController
{
    /**
     * 
     */
    public function __construct(private NormalizerInterface $normaliser) {
    }
    #[Route('/api/invstigators/v2/all', name: 'app_instigator_v2_all')]
    public function index(ManagerGetInstigator $manager): Response
    {
        $data = $manager->loadAllInvigotor();
        //dd($this->getUser());
       return new JsonResponse([
        "data" => $data
       ]);
    }
}
