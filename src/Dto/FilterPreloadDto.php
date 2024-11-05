<?php

/**
 * 
 */
namespace App\Dto;

use DateTime;

class FilterPreloadDto {


    private ?string $search = null;
    private array $cities = [];
    private array $strutures = [];
    private array $quarters = [];
    private array $towns = [];
    
    private ?\DateTime $dateStart = null;
    private ?\DateTime $dateEnd = null;

    private ?\DateTime $dateValidateStart = null;
    private ?\DateTime $dateValidateEnd = null;
    
    //datevalidatestart


    public function __construct() 
    {
        
    }

    /**
     * Get the value of cities
     */ 
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Set the value of cities
     *
     * @return  self
     */ 
    public function setCities($cities)
    {
        $this->cities = $cities;

        return $this;
    }


    /**
     * Get the value of towns
     */ 
    public function getTowns()
    {
        return $this->towns;
    }

    /**
     * Set the value of towns
     *
     * @return  self
     */ 
    public function setTowns($towns)
    {
        $this->towns = $towns;

        return $this;
    }

    /**
     * Get the value of dateStart
     */ 
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set the value of dateStart
     *
     * @return  self
     */ 
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get the value of dateEnd
     */ 
    public function getDateEnd()
    {
        if ($this->dateEnd) {
            //$this->dateEnd->modify("+1 day");
            return new DateTime($this->dateEnd->format("Y-m-") . (string) (((int) $this->dateEnd->format("d") + 1)));
        }
        return $this->dateEnd;
    }

    /**
     * Set the value of dateEnd
     *
     * @return  self
     */ 
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get the value of search
     */ 
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set the value of search
     *
     * @return  self
     */ 
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }


    /**
     * Get the value of dateValidateStart
     */ 
    public function getDateValidateStart()
    {
        return $this->dateValidateStart;
    }

    /**
     * Set the value of dateValidateStart
     *
     * @return  self
     */ 
    public function setDateValidateStart($dateValidateStart)
    {
        $this->dateValidateStart = $dateValidateStart;

        return $this;
    }

    /**
     * Get the value of dateValidateEnd
     */ 
    public function getDateValidateEnd()
    {
        return $this->dateValidateEnd;
    }

    /**
     * Set the value of dateValidateEnd
     *
     * @return  self
     */ 
    public function setDateValidateEnd($dateValidateEnd)
    {
        $this->dateValidateEnd = $dateValidateEnd;

        return $this;
    }

    /**
     * Get the value of strutures
     */ 
    public function getStrutures()
    {
        return $this->strutures;
    }

    /**
     * Set the value of strutures
     *
     * @return  self
     */ 
    public function setStrutures($strutures)
    {
        $this->strutures = $strutures;

        return $this;
    }

    /**
     * Get the value of quarters
     */ 
    public function getQuarters()
    {
        return $this->quarters;
    }

    /**
     * Set the value of quarters
     *
     * @return  self
     */ 
    public function setQuarters($quarters)
    {
        $this->quarters = $quarters;

        return $this;
    }
}



