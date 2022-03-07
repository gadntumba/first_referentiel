<?php

namespace App\Validators\Productor;

use App\Entity\Address;
use App\Validators\Util\Util;
use App\Entity\Monitor;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\Entity\HouseKeeping;
use App\Entity\Productor as EntityProductor;
use App\Validators\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


class Productor {
    
    private $personnalIdentityData; //PersonnalIdentityData
    /**
     * @var PieceOfIdentificationData
     */
    private $pieceOfIdentificationData; //PieceOfIdentificationData
    private $activityData; //ActivityData
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
     * @var float
     * @Assert\NotNull
     * @Assert\Type("float")
     */
    private $latitude;

    /**
     * @var float
     * @Assert\NotNull
     * @Assert\Type("float")
     */
    private $longitude;

    /**
     * @var float
     * @Assert\NotNull
     * @Assert\Type("float")
     */
    private $altitude;


    public function __construct(
        DenormalizerInterface $denormalizer,
        PersonnalIdentityData $personnalIdentityData,
        PieceOfIdentificationData $pieceOfIdentificationData,
        ActivityData $activityData,
        ValidatorInterface $validator
    )
    {
        $this->denormalizer = $denormalizer;
        $this->personnalIdentityData = $personnalIdentityData;
        $this->pieceOfIdentificationData = $pieceOfIdentificationData;
        $this->activityData = $activityData;
        $this->validator = $validator;
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
     * Set the value of activityData
     *
     * @return  self
     */ 
    public function setActivityData(array $activityData)
    {
        $activityData = $this->denormalizer->denormalize(
            $activityData, 
            ActivityData::class, 
            null, 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $this->activityData]
        );

        if (!($activityData instanceof ActivityData)) {
            throw new \Exception("Not supported yet");
        }

        $this->activityData = $activityData;

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

        $errorsTmp = $this->getActivityData()->validate();
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
        $productor->setIncumbentPhoto($persIdenProduct->getPhoto());
        $productor->setPhoneNumber($persIdenProduct->getPhone());
        $productor->setPhoneNumber($persIdenProduct->getPhone());
        $productor->setBirthdate($persIdenProduct->getBirthday());
        $productor->setNui($persIdenProduct->getNui());
        $productor->setLevelStudy($persIdenProduct->getLevelStudy());
        $productor->setHouseholdSize($persIdenProduct->getHouseholdSize());

        return $productor;
    }
    /**
     * 
     */
    public function addPieceOfIdentificationData(EntityProductor $productor)
    {
        $pieceOfIdentificationData = $this->getPieceOfIdentificationData();

        $productor->setNumberPieceOfIdentification($pieceOfIdentificationData->getPieceId());
        $productor->setTypePieceOfIdentification($pieceOfIdentificationData->getPieceIdentificationType());
        $productor->setPhotoPieceOfIdentification($pieceOfIdentificationData->getPhoto());

        return $productor;
    }

    public function addActivities(EntityProductor $productor)
    {
        //dd("hrhrhrhr");
        $productor = $this->getActivityData()->addAgricuturalsActivities($productor);
        $productor = $this->getActivityData()->addFichingsActivities($productor);
        $productor = $this->getActivityData()->addStockRaisingsActivities($productor);

        return $productor;
        
    }


    /**
     * Get the value of StockRaisings
    */ 
    public function getStockRaisings()
    {
        return $this->getActivityData()->getStockRaisings();
    }

    /**
     * Get the value of StockRaisings
    */ 
    public function getAgriculturals()
    {
        return $this->getActivityData()->getAgriculturals();
    }

    /**
     * Get the value of StockRaisings
    */ 
    public function getFichings()
    {
        return $this->getActivityData()->getFichings();
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function setAltitude(float $altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }



}