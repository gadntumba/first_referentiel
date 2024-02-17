<?php

/**
 * 
 */
namespace App\Dto;

class FilterUserDto {


    private ?string $search = null;
    private array $provinces = [];
    private array $cities = [];
    private array $territories = [];
    private array $sectors = [];
    private array $towns = [];
    private ?\DateTime $dateStart = null;
    private ?\DateTime $dateEnd = null;


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
            $this->dateEnd->modify("+1 day");
            return $this->dateEnd;
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
}



