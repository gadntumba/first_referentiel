<?php
namespace App\Validators\Productor;

use App\Entity\StockRaisingActivity;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;


class AdressData {
    
    private $rural; //Rural
    private $urbain; //Urbain
    private $latitude; //int
    private $longitude; //int
    private $altitude; //int
   

    private $town; //Gps
    private $sector; //Gps
    private $line; //Gps


    /**
     * Get the value of town
     */ 
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Get the value of sector
     */ 
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Get the value of line
     */ 
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Set the value of rural
     *
     * @return  self
     */ 
    public function setRural($rural)
    {
        $this->rural = $rural;

        return $this;
    }

    /**
     * Set the value of urbain
     *
     * @return  self
     */ 
    public function setUrbain($urbain)
    {
        $this->urbain = $urbain;

        return $this;
    }

    /**
     * Set the value of latitude
     *
     * @return  self
     */ 
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get the value of latitude
     */ 
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get the value of longitude
     */ 
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the value of longitude
     *
     * @return  self
     */ 
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get the value of altitude
     */ 
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * Set the value of altitude
     *
     * @return  self
     */ 
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;

        return $this;
    }
}
