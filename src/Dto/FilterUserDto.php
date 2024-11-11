<?php

/**
 * 
 */
namespace App\Dto;

use DateTime;

class FilterUserDto {


    private ?string $search = null;
    private array $provinces = [];
    private array $cities = [];
    private array $territories = [];
    private array $sectors = [];
    private array $towns = [];
    private array $invests = [];
    private array $activities = [];
    
    private ?\DateTime $dateStart = null;
    private ?\DateTime $dateEnd = null;

    private ?\DateTime $dateValidateStart = null;
    private ?\DateTime $dateValidateEnd = null;
    
    //datevalidatestart


    public function __construct() 
    {
        
    }

    /**
     * Get the value of provinces
     */ 
    public function getProvinces()
    {
        return $this->provinces;
    }

    /**
     * Set the value of provinces
     *
     * @return  self
     */ 
    public function setProvinces($provinces)
    {
        $this->provinces = $provinces;

        return $this;
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
     * Get the value of territories
     */ 
    public function getTerritories()
    {
        return $this->territories;
    }

    /**
     * Set the value of territories
     *
     * @return  self
     */ 
    public function setTerritories($territories)
    {
        $this->territories = $territories;

        return $this;
    }

    /**
     * Get the value of sectors
     */ 
    public function getSectors()
    {
        return $this->sectors;
    }

    /**
     * Set the value of sectors
     *
     * @return  self
     */ 
    public function setSectors($sectors)
    {
        $this->sectors = $sectors;

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
     * Get the value of invests
     */ 
    public function getInvests()
    {
        return $this->invests;
    }

    /**
     * Set the value of invests
     *
     * @return  self
     */ 
    public function setInvests($invests)
    {
        $this->invests = $invests;

        return $this;
    }

    /**
     * Get the value of activities
     */ 
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * Set the value of activities
     *
     * @return  self
     */ 
    public function setActivities($activities)
    {
        $this->activities = $activities;

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
}



