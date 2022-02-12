<?php
namespace App\Validators\Productor;


class LevelStudy {
    private $id; //String
    private $libele; //String
   

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of libele
     */ 
    public function getLibele()
    {
        return $this->libele;
    }

    /**
     * Set the value of libele
     *
     * @return  self
     */ 
    public function setLibele($libele)
    {
        $this->libele = $libele;

        return $this;
    }
}