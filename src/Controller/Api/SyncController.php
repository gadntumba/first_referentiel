<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SyncController extends AbstractController
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    public function __construct(NormalizerInterface $normalizer) {
        $this->normalizer = $normalizer;
    }
    #[Route('/api/sync', name: 'app_api_sync')]
    public function index(): Response
    {

        return $this->render('api/sync/index.html.twig', [
            'controller_name' => 'SyncController',
        ]);
    }
}
