<?php
namespace App\Services;

use App\Entity\Instigator;
use App\Entity\Productor;
use App\Repository\InstigatorRepository;
use App\Repository\ProductorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ManagerGetInstigator 
{
    public function __construct(
        private EntityManagerInterface $em,
        private HttpClientInterface $httpClient,
        private ProductorRepository $productorRepository,
        private ContainerBagInterface $containerBag,
        private InstigatorRepository $instigatorRepository
    ) 
    {
        
    }

    function getIfNotExist() {
        /**
         * @var Productor[]
         */
        $productorConcerns = $this->productorRepository->findIfInstigatorIsNull();
        
        //dd(count($productorConcerns));
        $host = $this->containerBag->get("agromwinda_host");
        $host = "https://api.agromwinda.com";

        foreach ($productorConcerns as $key => $productorConcern) {
            $investigatorId = $productorConcern->getInvestigatorId();
            $instigator = $this->instigatorRepository->findOneBy(["phoneNumber" => $investigatorId]);
            if (is_null($instigator) ) {
                $instigator = $this->loadInvigotor($productorConcern);
                dump("load instigator : " . $investigatorId);
            }

            $productorConcern->setInstigator($instigator);

            dump("set investigator : ".$instigator->getPhoneNumber()." in produtor : ". $productorConcern->getId());
        }

        $this->em->flush();

    }

    function loadInvestigator(Productor $productor) {
        $investigatorId = $productor->getInvestigatorId();

        $instigator = $this->instigatorRepository->findOneBy(["phoneNumber" => $investigatorId]);
        
        if (is_null($instigator) ) {
            $instigator = $this->loadInvigotor($productor);
        }

        $productor->setInstigator($instigator);
        
    }

    function loadInvigotor(Productor $productor) : ?Instigator 
    {
        $investigatorId = $productor->getInvestigatorId();

        $host = $this->containerBag->get("agromwinda_host");
        $host = "https://api.agromwinda.com";
        $response = $this->httpClient->request(
            "GET",
            $host."/users/$investigatorId",
            [
                "headers" => [
                    //"Content-Type" => "application/json",
                ],
            ]
        );
        $statusCode = $response->getStatusCode();
        $isOK = $statusCode >=200 && 300 > $statusCode;
        try {
            $arr = $response->toArray(false);

            //dd($arr["user"]);

            if ($isOK && isset($arr["user"])) {
                $investigator = new Instigator();
                $investigator->setName(isset($arr["user"]["name"]) ? $arr["user"]["name"] :null );
                $investigator->setFirstname(isset($arr["user"]["firstname"]) ? $arr["user"]["firstname"] : null);
                $investigator->setLastname(isset($arr["user"]["lastname"]) ? $arr["user"]["lastname"] : null);
                $investigator->setPhoneNumber(isset($arr["user"]["phone_number"])? $arr["user"]["phone_number"] : null);
                $this->em->persist($investigator);
                $this->em->flush();
                return $investigator;
            }else {
                return null;

            }
            
        } catch (\Throwable $th) {
            return null;
            //throw $th;
        }

        
    }



}