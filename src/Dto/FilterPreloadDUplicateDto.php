<?php

/**
 * 
 */
namespace App\Dto;

use DateTime;

class FilterPreloadDUplicateDto {

    private ?string $search = null;
    private array $cities = [];
    
    private ?\DateTime $dateStart = null;
    private ?\DateTime $dateEnd = null;

    private bool $isNotAss = false;

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
     * Get the value of isNotAss
     */ 
    public function getIsNotAss()
    {
        return $this->isNotAss;
    }

    /**
     * Set the value of isNotAss
     *
     * @return  self
     */ 
    public function setIsNotAss($isNotAss)
    {
        $this->isNotAss = $isNotAss;

        return $this;
    }
}



