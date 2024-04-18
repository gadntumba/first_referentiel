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
            $instigatorOld = $this->instigatorRepository->findOneBy(["phoneNumber" => $investigatorId]);
            $instigator = null;

            if (is_null($instigatorOld) ) 
            {
                $instigator = $this->loadInvigotor($productorConcern);
                dump("load instigator : " . $investigatorId);
            }

            if (is_null($instigatorOld)) 
            {
                $instigator = $this->loadInvigotor($productorConcern, "0");  
                dump("load instigator (with prefix 0) : " . $investigatorId);              
            }

            if (is_null($instigator)) 
            {
                $instigator = $this->loadInvigotor($productorConcern, "", $instigatorOld);
            }

            //$instigator = $this->loadInvigotor($productorConcern, "", $instigator); 

            $productorConcern->setInstigator($instigator);

            dump("set investigator : ".$instigator?->getPhoneNumber()." in produtor : ". $productorConcern->getId());
        }

        $this->em->flush();

    }

    function loadInvestigator(Productor $productor) {
        $investigatorId = $productor->getInvestigatorId();

        $instigator = $this->instigatorRepository->findOneBy(["phoneNumber" => $investigatorId]);
        
        if (is_null($instigator) ) {
            $instigator = $this->loadInvigotor($productor);
        }
        if (is_null($instigator)) {
            $instigator = $this->loadInvigotor($productor, "0");
            
        }

        $productor->setInstigator($instigator);
        
    }

    function loadInvigotor(Productor $productor, string $prefixId="", Instigator $investigator=null) : ?Instigator 
    {
        $investigatorId = $prefixId . $productor->getInvestigatorId();

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
            if (is_null($investigator)) {
                $investigator = new Instigator();
                $this->em->persist($investigator);
            }

            if ($isOK && isset($arr["user"])) 
            {
                //$investigator = new Instigator();
                $investigator->setName(isset($arr["user"]["name"]) ? $arr["user"]["name"] :null );
                $investigator->setFirstname(isset($arr["user"]["firstname"]) ? $arr["user"]["firstname"] : null);
                $investigator->setLastname(isset($arr["user"]["lastname"]) ? $arr["user"]["lastname"] : null);
                //$investigator->setPhoneNumber(isset($arr["user"]["phone_number"])? $arr["user"]["phone_number"] : null);
                $investigator->setPhoneNumber($productor->getInvestigatorId());
                //productor
                return $investigator;

            }else 
            {
                return null;

            }
            $this->em->flush();
            
        } catch (\Throwable $th) {
            return null;
            //throw $th;
        }

        
    }



}