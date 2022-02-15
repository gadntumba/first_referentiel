<?php
namespace App\Validators\Productor;

use App\Entity\AgriculturalActivity;
use App\Entity\FichingActivity;
use App\Entity\PieceIdentificationType;
use App\Entity\StockRaisingActivity;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Validators\Util\Util;
use App\Entity\Productor as EntityProductor;

class ActivityData {

    private $agriculturals; //array( Agriculturals )
    private $stockRaisings; //array( StockRaisings )
    private $fichings; //array( Fichings )
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer; //int

    public function __construct(DenormalizerInterface $denormalizer, ValidatorInterface $validator)
    {
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
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
 /**
     * 
     */
    public function validate()
    {
        $errors = [];

        $errors = $this->validateAgriculturalActivity($errors);
        $errors = $this->validateFichingActivity($errors);
        $errors = $this->validateStockRaisings($errors);

        return $errors;

    }
    /**
    * 
    */
    public function validateAgriculturalActivity(array $errors = [])
    {

        $actitvies = $this->getAgriculturals();

        foreach ($actitvies as $key => $activity) {

            $errorsTmp = $this->validator->validate($activity);


            if (count($errorsTmp) > 0) {
    
                $errors["activities"] = $errors["activities"]??[];
                $errors["activities"]["agricultural"] = $errors["activities"]["agricultural"]??[];
                $errors["activities"]["agricultural"][$key] = Util::tranformErrorsData($errorsTmp);
                
            
            }

        }
        
        return $errors;
        

    }
    /**
    * 
    */
    public function validateStockRaisings(array $errors = [])
    {

        $actitvies = $this->getStockRaisings();

        foreach ($actitvies as $key => $activity) {

            $errorsTmp = $this->validator->validate($activity);

            if (count($errorsTmp) > 0) {
    
                $errors["activities"] = $errors["activities"]??[];
                $errors["activities"]["stockRaisings"] = $errors["activities"]["stockRaisings"]??[];
                $errors["activities"]["stockRaisings"][$key] = Util::tranformErrorsData($errorsTmp);
            
            }
        }

        return $errors;

    }
    /**
    * 
    */
    public function validateFichingActivity(array $errors = [])
    {

        $actitvies = $this->getFichings();

        foreach ($actitvies as $key => $activity) {

            $errorsTmp = $this->validator->validate($activity);

            if (count($errorsTmp) > 0) {
    
                $errors["activities"] = $errors["activities"]??[];
                $errors["activities"]["fichingActivities"] = $errors["activities"]["fichingActivities"]??[];
                $errors["activities"]["fichingActivities"][$key] = Util::tranformErrorsData($errorsTmp);
            
            }

        }

        return $errors;

    }



    /**
     * 
     */
    public function addAgricuturalsActivities(EntityProductor $productor)
    {
        $activities = $this->getAgriculturals();

        foreach ($activities as $key => $activity) {
            
                if ($activity instanceof AgriculturalActivity) {                
                    $activity->setProductor($productor);

                }

        }

        return $productor;
    }

    public function addFichingsActivities(EntityProductor $productor)
    {
        $activities = $this->getFichings();

        foreach ($activities as $key => $activity) {
            
            if ($activity instanceof FichingActivity) {       

                $activity->setProductor($productor);
            }

        }
        return $productor;
    }
    /**
     * 
     */
    public function addStockRaisingsActivities(EntityProductor $productor)
    {
        $activities = $this->getStockRaisings();

        foreach ($activities as $key => $activity) {
            
            if ($activity instanceof StockRaisingActivity) {                
                $activity->setProductor($productor);
            }

        }
        
        return $productor;
        
    }
}