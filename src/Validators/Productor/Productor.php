<?php

namespace App\Validators\Productor;

use App\Entity\Monitor;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;


class Productor {
    
    private $personnalIdentityData; //PersonnalIdentityData
    private $pieceOfIdentificationData; //PieceOfIdentificationData
    private $adressData; //AdressData
    private $activityData; //ActivityData
    private $monitor; //Monitor
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer; //int


    public function __construct(
        DenormalizerInterface $denormalizer,
        PersonnalIdentityData $personnalIdentityData,
        PieceOfIdentificationData $pieceOfIdentificationData,
        AdressData $adressData,
        ActivityData $activityData
    )
    {
        $this->denormalizer = $denormalizer;
        $this->personnalIdentityData = $personnalIdentityData;
        $this->pieceOfIdentificationData = $pieceOfIdentificationData;
        $this->adressData = $adressData;
        $this->activityData = $activityData;
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
    public function setPieceOfIdentificationData(array $pieceOfIdentificationData)
    {   
        $pieceOfIdentificationData = $this->denormalizer->denormalize(
            $pieceOfIdentificationData, 
            PieceOfIdentificationData::class, 
            null,
            [AbstractNormalizer::OBJECT_TO_POPULATE => $this->pieceOfIdentificationData]
        );

        if (!($pieceOfIdentificationData instanceof PieceOfIdentificationData)) {
            throw new \Exception("Not supported yet");
        }

        $this->pieceOfIdentificationData = $pieceOfIdentificationData;

        return $this;
    }

    /**
     * Get the value of adressData
     */ 
    public function getAdressData()
    {
        return $this->adressData;
    }

    /**
     * Set the value of adressData
     *
     * @return  self
     */ 
    public function setAdressData(array $adressData)
    {   
        $adressData = $this->denormalizer->denormalize($adressData, AdressData::class, null, []);

        if (!($adressData instanceof AdressData)) {
            throw new \Exception("Not supported yet");
        }

        $this->adressData = $adressData;

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
}