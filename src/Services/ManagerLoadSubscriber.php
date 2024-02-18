<?php
 
namespace App\Services;

use App\Command\InsertAgromwindaPlacesCommand;
use App\Entity\Productor;
use App\Entity\User;
use App\Message\SendLoadSubscriberInAgromwinda;
use App\Repository\ProductorRepository;
use App\Repository\SectorRepository;
use App\Repository\TownRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Mink67\MultiPartDeserialize\Services\MultiPartNormalizer;
use Symfony\Component\Messenger\MessageBusInterface;

class ManagerLoadSubscriber 
{
    const AGE_RANGE=["Moins de 18 ans","18 à 35 ans","35 à 45 ans","Plus de 45 ans"];
    const LEVEL_OF_STADY_RANGE=["Aucune","Primaire","Sécondaires","Universitaire"];
    const SEXE_RANGE = ["M" => "Homme","F"  =>  "Femme"];

    public function __construct(
        private EntityManagerInterface $em,
        private ContainerBagInterface $containerBag,
        private HttpClientInterface $httpClient,
        private NormalizerInterface $normalizer,
        private CacheManager $cacheManager,
        private MultiPartNormalizer $multiPartNormalizer,
        private MessageBusInterface $messageBus,
        private InsertAgromwindaPlacesCommand $managerMatcherLocation,
        private TownRepository $townRepository,
        private SectorRepository $sectorRepository,
    ) 
    {
        
    }


    public function load(Productor $productor) : void 
    {

        if (!is_null($productor->getRemoteId())) 
        {
            return;
        }

        $birthDate = $productor->getBirthdate();
        //dd($birthDate);
        $loadMode = $this->containerBag->get("agromwinda_load_mode") == "TEST"? "test@" : "";

        $data = [];
        $data["email"] = null;
        $data["name"] = $loadMode . $productor->getName();
        $data["firstname"] = $productor->getFirstName();
        $data["lastname"] = $productor->getLastName();
        $data["sexe"] = self::SEXE_RANGE[$productor->getSexe()];
        $data["phoneNumber"] = $productor->getPhoneNumber();
        $data["age"] = $this->getAgeMatching($birthDate);
        $data["levelOfStudy"] = "Universitaire";
        $data["agentProcessPhoneNumber"] = $productor->getInvestigatorId();
        $houseKeeping = $productor?->getHousekeeping();
        $address = $houseKeeping?->getAddress();
        $iriTown = null;
        $iriSector = null;

        if ($address?->getTown()) {
            $town = $address?->getTown();
            $townId = $town->getId();
            //dd($townId);
            $res = $this->managerMatcherLocation->getParamByAppId("/towns", $townId);
            $iriTown = "/api".$res->getIri();
            
        }elseif ($address?->getSector()) {
            $sector = $address?->getSector();
            $sectorId = $sector->getId();
            //dd($sectorId);
            $res = $this->managerMatcherLocation->getParamByAppId("/sectors", $sectorId);
            $iriSector = "/api".$res->getIri();
            
        }

        //dd("/api".$res->getIri());
        
        $data["address"] = [
            "home" => $address?->getLine(),
            "avenue" => $address?->getLine(),
            "village" => $address?->getLine(),
            "quarter" => $address?->getLine(),
            "town" => $iriTown,
            "groupment" => $iriSector,
        ];  
        $data["rnaId"] = $productor->getId();
        //

        $images = $this->normalizer->normalize(
            $productor, 
            null, 
            [
                'groups' => ["read:producer:image"]
            ]            
        );

        $images = $this->multiPartNormalizer->normalize($productor, $images);
        //dd($images);


        $photoHost = $this->containerBag->get("photo_host");

        try {

            $data["profilPic"] = [
                "path" => $photoHost . $images["photoPieceOfIdentification"]["pic_identity"]
            ];
            
        } catch (\Throwable $th) {
            
        }

        $host = $this->containerBag->get("agromwinda_host");

        //dd($host);
        //dd($data);

        $response = $this->httpClient->request(
            "POST",
            $host."/api/subscribers/rna/load",
            [
                "json" => $data,
                "headers" => [
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $statusCode = $response->getStatusCode();
        $isOK = $statusCode >=200 && 300 > $statusCode;
        try {
            $arr = $response->toArray(false);

            if ($isOK) {
                $message = "OK";
            }else {
                $message = $arr["hydra:description"];

            }
            
        } catch (\Throwable $th) {
            $message = $response->getContent(false);
            //throw $th;
        }

        //dd($response->getStatusCode());

        $productor->setReturnStatusCode($response->getStatusCode());
        $productor->setReturnMessage($message);

        //dd($productor);

        if ($statusCode >=200 && 300 > $statusCode) {
            $arr = $response->toArray(false);
            //dd($arr["id"]);
            $productor->setRemoteId((int) $arr["id"]); 
        }
        //$productor->setRemoteId($message);
        $this->em->flush();

        //$this->sendEventLoadIfNot();

    }

    public function loadIfNotLoading() : void 
    {
        /**
         * @var ProductorRepository
         */
        $repository = $this->em->getRepository(Productor::class);
        
        
        $productors = $repository->findNotLoad();

        foreach ($productors as $key => $productor) {
            $this->load($productor);
        }
        
    }

    
    public function sendEventLoad(Productor $productor) : void 
    {
        $this->messageBus->dispatch(
            new SendLoadSubscriberInAgromwinda($productor->getId())
        );
        
    }
    
    public function sendEventLoadIfNot() : void 
    {
        /**
         * @var ProductorRepository
         */
        $repository = $this->em->getRepository(Productor::class);
        
        
        $productors = $repository->findNotLoad();

        foreach ($productors as $key => $productor) {
            $this->sendEventLoad($productor);
        }
        
    }

    private function getAgeMatching(DateTimeInterface $date) : string {
        $timpstamp = $date->getTimestamp();
        $year = 1 + $timpstamp/ (365*24*60*60);
        //dd($year);
        $i = 0;
        if (18 > $year ) 
        {
            $i = 0;  
        }else if(35 > $year && $year >= 18) 
        {
            $i = 1;
        }else if(45 > $year && $year >= 35) 
        {
            $i = 2;
        }else if($year >= 45) 
        {
            $i = 3;
        }

        return self::AGE_RANGE[$i];
    }

    
}