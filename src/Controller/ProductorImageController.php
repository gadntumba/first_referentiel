<?php

namespace App\Controller;
use App\Entity\Productor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductorImageController extends AbstractController
{
    
    public function __invoke(Request $request)
    {
        $productor = $request->attributes->get('data');
        if (!($productor instanceof Productor)) {
            throw new \RuntimeException('Article entendu');
        }
        $productor->setImageFile($request->files->get('imageFile'));
        $productor->setUpdatedAt(new \DateTime());
        
        return $productor;
    }
}
