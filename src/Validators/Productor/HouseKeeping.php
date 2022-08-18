<?php
namespace App\Validators\Productor;

class HouseKeeping {
    /**
     * @var string
     */
 private $nim; //String

 /**
  * Get the value of nim
  *@return string
  */ 
 public function getNim()
 {
  return $this->nim;
 }

 /**
  * Set the value of nim
  *
  * @return  self
  *@param string $nim 
  */ 
 public function setNim(string $nim)
 {
  $this->nim = $nim;

  return $this;
 }
}