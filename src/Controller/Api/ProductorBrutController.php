<?php

namespace App\Controller\Api;

use App\Entity\Productor;
use App\Entity\ProductorBrut;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductorBrutController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {
        
    }
    
    public function __invoke(ProductorBrut $data): ProductorBrut
    {
        //dd($data);
        /**
         * @var OAuthUser
         */
        $user =  $this->getUser();

        if (!$this->isGranted("ROLE_INVESTIGATOR")) 
        {
          throw new HttpException(403, "No access");  
        }

        $data->setInvestigatorId($user->getNormalUsername());

        //$this->em->persist($data);
        //$this->em->flush();

        return $data;
    }
}