<?php

namespace App\Validators\Productor;

use App\Entity\Address;
use App\Validators\Util\Util;
use App\Entity\Monitor;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\Entity\HouseKeeping;
use App\Entity\Productor as EntityProductor;
use App\Entity\ProductorPreload;
use App\Services\FileUploader;
use App\Validators\Exception\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


class Productor {
    
    private $personnalIdentityData; //PersonnalIdentityData
    /**
     * @var PieceOfIdentificationData
     */
    private $pieceOfIdentificationData; //PieceOfIdentificationData
    /**
     * @var array
     */
    private $activityData; //ActivityData
    /**
     * 
     */
    private $activityDataEntity; //ActivityData

    private $monitor; //Monitor
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer; //int
    /**
     * @var HouseKeeping
     */
    private $houseKeeping; //HouseKeeping
    /**
     * @var ValidatorInterface
     */
    private $validator; //HouseKeeping
    /**
     * @var FileUploader
     */
    private $fileUploader; //HouseKeeping
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @Assert\NotNull
     * @Assert\Type("float")
     */
    private $latitude;

    /**
     * @Assert\NotNull
     * @Assert\Type("float")
     */
    private $longitude;

    /**
     * @Assert\NotNull
     * @Assert\Type("float")
     */
    private $altitude;

    /**
     * @Assert\File(
     *  maxSize="10M"
     * )
     * @Assert\NotNull
     */
    //* 
    private $photoPieceOfIdentification;

    /**
     * @Assert\File(
     *  maxSize="10M"
     * )
     * @Assert\NotNull
     */
    //* 
    private $incumbentPhoto;


    public function __construct(
        DenormalizerInterface $denormalizer,
        PersonnalIdentityData $personnalIdentityData,
        PieceOfIdentificationData $pieceOfIdentificationData,
        ActivityData $activityDataEntity,
        ValidatorInterface $validator,
        LoggerInterface $logger,
        FileUploader $fileUploader
    )
    {
        $this->denormalizer = $denormalizer;
        $this->personnalIdentityData = $personnalIdentityData;
        $this->pieceOfIdentificationData = $pieceOfIdentificationData;
        $this->activityDataEntity = $activityDataEntity;
        $this->validator = $validator;
        $this->fileUploader = $fileUploader;
        $this->logger = $logger;
        //dd($activityData);
    }

    /**
     * Get the value of personnalIdentityData
     */ 
    public function getPersonnalIdentityData()
    {
        return $this->personnalIdentityData;
    }

    /**
     * Set the value of personnalIdentityData
     *
     * @return  self
     */ 
    public function setPersonnalIdentityData(array $personnalIdentityData)
    {   
        //dd($personnalIdentityData);
        $personnalIdentityData = $this->denormalizer->denormalize(
            $personnalIdentityData, 
            PersonnalIdentityData::class, 
            null, 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $this->personnalIdentityData]
        );


        if (!($personnalIdentityData instanceof PersonnalIdentityData)) {
            throw new \Exception("Not supported yet");
        }

        $this->personnalIdentityData = $personnalIdentityData;

        return $this;
    }

    /**
     * Get the value of pieceOfIdentificationData
     */ 
    public function getPieceOfIdentificationData()
    {
        return $this->pieceOfIdentificationData;
    }

    /**
     * Set the value of pieceOfIdentificationData
     *
     * @return  self
     */ 
    public function setPieceOfIdentificationData(PieceOfIdentificationData $pieceOfIdentificationData)
    {

        $this->pieceOfIdentificationData = $pieceOfIdentificationData;

        return $this;
    }
    /**
     * Get the value of activityData
     */ 
    public function getActivityData()
    {
        return $this->activityData;
    }
    /**
     * Get the value of activityData
     */ 
    public function getActivityDataEntity()
    {
        return $this->activityDataEntity;
    }

    /**
     * Set the value of activityData
     *
     * @return  self
     */ 
    public function setActivityData($activityData)
    {
        /*dd($activityData);
        
        if (!is_array($activityData)) {
            $this->logger->info(serialize($activityData));
            throw new BadRequestHttpException("activityData must be array type");            
        }*/
        $this->activityData = $activityData;


        $activityDataEntity = $this->denormalizer->denormalize(
            $activityData, 
            ActivityData::class, 
            null, 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $this->activityDataEntity]
        );

        if (!($activityDataEntity instanceof ActivityData)) {
            throw new \Exception("Not supported yet");
        }

        $this->activityDataEntity = $activityDataEntity;

        return $this;
    }

    /**
     * Get the value of monitor
     */ 
    public function getMonitor()
    {
        return $this->monitor;
    }

    /**
     * Set the value of monitor
     *
     * @return  self
     */ 
    public function setMonitor(array $monitor)
    {
        
        $monitor = $this->denormalizer->denormalize($monitor, Monitor::class, null, []);

        if (!($monitor instanceof Monitor)) {
            throw new \Exception("Not supported yet");
        }

        $this->monitor = $monitor;

        return $this;
    }

    /**
     * Get the value of houseKeeping
     */ 
    public function getHouseKeeping()
    {
        return $this->houseKeeping;
    }

    /**
     * Set the value of houseKeeping
     *
     * @return  self
     */ 
    public function setHouseKeeping(HouseKeeping $houseKeeping)
    {
        $this->houseKeeping = $houseKeeping;
        return $this;
    }
    /**
     * 
     */
    public function validate()
    {
        //validate housekeeping data

        if (is_null($this->houseKeeping)) {
            $this->houseKeeping = new HouseKeeping;
        }

        if (is_null($this->houseKeeping->getAddress())) {
            $address = new Address;
            $this->houseKeeping->setAddress($address);
        }

        $errorsTmp = $this->validator->validate($this);
        $errors = (count($errorsTmp) > 0)? Util::tranformErrorsData($errorsTmp) : [];

        $errorsTmp = $this->validator->validate($this->houseKeeping);

        if (count($errorsTmp) > 0) {
            $errors["houseKeeping"] = Util::tranformErrorsData($errorsTmp);
        }
        //validate and persist address

        $address = $this->houseKeeping->getAddress();
        $errorsTmp = $this->validator->validate($address);

        if (count($errorsTmp) > 0) {

            $errors["houseKeeping"] = $errors["houseKeeping"]??[];
            $errors["houseKeeping"]["address"] = Util::tranformErrorsData($errorsTmp);
        
        }

        if (
            is_null($address->getTown()) &&
            is_null($address->getSector()) 
        ) {

            $errors["houseKeeping"] = $errors["houseKeeping"]??[];
            $errors["houseKeeping"]["address"] = $errors["houseKeeping"]["address"]??[];

            $errors["houseKeeping"]["address"]["location"] = [
                [
                    "code" => "ad32d13f-c3d4-423b-909a-857b961eb7443",
                    "message" => "This value should not be null."
                ]
            ];
        }


        //validate the personnality identity data
        $idPers = $this->getPersonnalIdentityData();
        $errorsTmp = $this->validator->validate($idPers);

        if (count($errorsTmp) > 0) {

            $errors["personnalIdentityData"] = Util::tranformErrorsData($errorsTmp);
        
        }
        //dd($errorsTmp);
        
        //validate piece Of identification Data
        $pieceId = $this->getPieceOfIdentificationData();
        $errorsTmp = $this->validator->validate($pieceId);

        if (count($errorsTmp) > 0) {

            $errors["pieceOfIdentificationData"] = Util::tranformErrorsData($errorsTmp);
        
        }

        $errorsTmp = $this->getActivityDataEntity()->validate();
        //validate activity data
        if (isset($errorsTmp["activities"])) {

            $errors["activities"] = $errorsTmp["activities"];
        
        }

        
        if (count($errors) > 0) {
            
            throw new Exception($errors);
            
        }

        //persist address
        //persist housekeeping

        //add the personnality identity data
        //add piece Of identification Data
        //persist productor data
    }

    /**
     * 
     */
    public function addPersonnalIdentification(EntityProductor $productor)
    {
        $persIdenProduct = $this->getPersonnalIdentityData();
        $productor->setName($persIdenProduct->getName());
        $productor->setFirstName($persIdenProduct->getFirstName());
        $productor->setLastName($persIdenProduct->getLastName());
        $productor->setSexe($persIdenProduct->getSexe());
        $productor->setIncumbentPhoto($this->getPathFile($this->getIncumbentPhoto()));
        $productor->setPhoneNumber($persIdenProduct->getPhone());
        $productor->setPhoneNumber($persIdenProduct->getPhone());
        $productor->setBirthdate($persIdenProduct->getBirthday());
        $productor->setNui($persIdenProduct->getNui());
        $productor->setLevelStudy($persIdenProduct->getLevelStudy());
        $productor->setHouseholdSize($persIdenProduct->getHouseholdSize());
        $productor->setOrganization($persIdenProduct->getOrganization());

        return $productor;
    }

    public static function normalPhone(string $text) : string {

        $phone = ProductorPreload::hideSpaces($text);
        $phone = str_replace("+", '', $phone);


        if (empty($phone)) {
            return "";
        }
        //dump(strpos($phone, '243') != "");
        
        if (strpos($phone, '00') === 0) {
            //dump("00");
            $phone = substr($phone, 2);
        }
        
        if (strpos($phone, '243')  === 0) {
            //dump("243");
            $phone = substr($phone, 3);
        }else if (strpos($phone, '0')  === 0) {
            $phone = substr($phone, 1);
        }
        return $phone;
    }
    /**
     * 
     */
    public function addPieceOfIdentificationData(EntityProductor $productor)
    {
        $pieceOfIdentificationData = $this->getPieceOfIdentificationData();

        $productor->setNumberPieceOfIdentification($pieceOfIdentificationData->getPieceId());
        $productor->setTypePieceOfIdentification($pieceOfIdentificationData->getPieceIdentificationType());
        $productor->setPhotoPieceOfIdentification($this->getPathFile($this->getPhotoPieceOfIdentification()));

        return $productor;
    }
    /**
     * 
     */
    private function getPathFile($uploadedFile)
    {
        if (!$uploadedFile) {
            throw new BadRequestHttpException(' is required');
        }

        return $this->fileUploader->uploadGoogle($uploadedFile);
    }

    public function addActivities(EntityProductor $productor)
    {
        //dd("hrhrhrhr");
        $productor = $this->getActivityDataEntity()->addAgricuturalsActivities($productor);
        $productor = $this->getActivityDataEntity()->addFichingsActivities($productor);
        $productor = $this->getActivityDataEntity()->addStockRaisingsActivities($productor);
        $productor = $this->getActivityDataEntity()->addEntreneurialActivities($productor);
        //

        return $productor;
        
    }


    /**
     * Get the value of StockRaisings
    */ 
    public function getStockRaisings()
    {
        return $this->getActivityDataEntity()->getStockRaisings();
    }

    /**
     * Get the value of StockRaisings
    */ 
    public function getAgriculturals()
    {
        return $this->getActivityDataEntity()->getAgriculturals();
    }

    /**
     * Get the value of StockRaisings
    */ 
    public function getFichings()
    {
        return $this->getActivityDataEntity()->getFichings();
    }
    /**
     * Get the value of StockRaisings
    */ 
    public function getEntrepreneurships()
    {
        return $this->getActivityDataEntity()->getEntrepreneurships();
    }
    //getEntrepreneurships

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude): self
    {
        //dd($latitude);
        $this->latitude = floatval($latitude);

        return $this;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude): self
    {
        $this->longitude = floatval($longitude);
        

        return $this;
    }

    public function getAltitude()
    {
        return $this->altitude;
    }

    public function setAltitude( $altitude): self
    {
        $this->altitude = floatval($altitude);

        return $this;
    }


    /**
     * Get the value of photoPieceOfIdentification
     */ 
    public function getPhotoPieceOfIdentification()
    {
        return $this->photoPieceOfIdentification;
    }

    /**
     * Set the value of photoPieceOfIdentification
     *
     * @return  self
     */ 
    public function setPhotoPieceOfIdentification($photoPieceOfIdentification)
    {
        $this->photoPieceOfIdentification = $photoPieceOfIdentification;

        return $this;
    }

    /**
     * Get the value of incumbentPhoto
     */ 
    public function getIncumbentPhoto()
    {
        return $this->incumbentPhoto;
    }

    /**
     * Set the value of incumbentPhoto
     *
     * @return  self
     */ 
    public function setIncumbentPhoto($incumbentPhoto)
    {
        $this->incumbentPhoto = $incumbentPhoto;

        return $this;
    }
}