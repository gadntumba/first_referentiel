<?php
namespace App\Validators\Productor;

use App\Entity\AgriculturalActivity;
use App\Entity\FichingActivity;
use App\Entity\PieceIdentificationType;
use App\Entity\StockRaisingActivity;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ActivityData {
 private $agriculturals; //array( Agriculturals )
 private $stockRaisings; //array( StockRaisings )
 private $Fichings; //array( Fichings )
    /**
 * @var DenormalizerInterface
 */
    private $denormalizer; //int


    public function __construct(DenormalizerInterface $denormalizer)
    {
        $this->denormalizer = $denormalizer;
    }


 /**
  * Get the value of Agriculturals
  */ 
 public function getAgriculturals()
 {
  return $this->agriculturals;
 }

 /**
  * Set the value of Agriculturals
  *
  * @return  self
  */ 
 public function setAgriculturals(array $agriculturals)
 {
    $newAgriculturals = [];

    //dd($agriculturals);
    
    foreach ($agriculturals as $key => $agricultural) {
        
            $agricultural = $this->denormalizer->denormalize($agricultural, AgriculturalActivity::class, null, []);
                
            if (!($agricultural instanceof AgriculturalActivity)) {
                throw new \Exception("Not supported yet");
            }

            array_push($newAgriculturals, $agricultural );
    }

    $this->agriculturals = $newAgriculturals;
    return $this;
 }

 /**
  * Get the value of StockRaisings
  */ 
 public function getStockRaisings()
 {
  return $this->stockRaisings;
 }

 /**
  * Set the value of StockRaisings
  *
  * @return  self
  */ 
 public function setStockRaisings($stockRaisings)
 {
  $newStockRaising = [];
  
  foreach ($stockRaisings as $key => $stockRaising) {
            
          $stockRaising = $this->denormalizer->denormalize($stockRaising, StockRaisingActivity::class, null, []);
              
          if (!($stockRaising instanceof StockRaisingActivity)) {
              throw new \Exception("Not supported yet");
          }

          array_push($newStockRaising, $stockRaising );
  }

  $this->stockRaisings = $newStockRaising;
  return $this;
 }

 /**
  * Get the value of Fichings
  */ 
 public function getFichings()
 {
  return $this->fichings;
 }

 /**
  * Set the value of Fichings
  *
  * @return  self
  */ 
 public function setFichings($fichings)
 {
  $this->fichings = $fichings;
  $newFichings = [];
  
  foreach ($fichings as $key => $fiching) {
      
    $fiching = $this->denormalizer->denormalize($fiching, FichingActivity::class, null, []);

    //dd($fiching);
    if (!($fiching instanceof FichingActivity)) {
        throw new \Exception("Not supported yet");
    }

    array_push($newFichings, $fiching );
  }

  $this->fichings = $newFichings;
  return $this;
 }
}