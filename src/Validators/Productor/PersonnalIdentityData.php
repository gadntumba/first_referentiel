<?php
namespace App\Validators\Productor;

use App\Entity\LevelStudy;
use App\Entity\MaritalState;
use App\Entity\Organization;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
/**
 */
class PersonnalIdentityData {
    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type("string")
     */
    private $name; //String
    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type("string")
     */
    private $firstName; //String
    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type("string")
     */
    private $lastName; //String
    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type("string")
     */
    private $sexe; //String
    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type("string")
     */
    private $phone; //String
    /**
     * @Assert\NotNull
     * @Assert\NotBlank
     * )
     */
    private $birthday; //String
    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type("string")
     */
    private $nui; //String
    /**
     */
    private $photo; //String
    /**
     * @Assert\NotNull
     * @var LevelStudy
     */
    private $levelStudy; //LevelStudy
    /**
     * @Assert\NotNull
     * @Assert\Type("int")
     */
    private $householdSize; //int

    /**
     * @var DenormalizerInterface
     */
    private $denormalizer; //int
    /**
     * @var MaritalState
     */
    private $maritalState;

    /**
     * @var Organization
     */
    private $organization;



    
    public function __construct(DenormalizerInterface $denormalizer)
    {
        
        $this->denormalizer = $denormalizer;
    }
     /**
     * @Assert\Callback
     */
    public function validateDates(ExecutionContextInterface $context): void
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2}):(\d{2})(?:([\+\-]\d{2}:\d{2})|Z)?)?$/', $this->birthday, $matches)) {
            
        } else {
            $context->buildViolation('invalid date '.$this->birthday)
                ->atPath('birthday')
                ->addViolation();
        }
    }

 /**
  * Get the value of name
  */ 
 public function getName()
 {
  return $this->name;
 }

 /**
  * Set the value of name
  *
  * @return  self
  */ 
 public function setName($name)
 {
  $this->name = $name;

  return $this;
 }

 /**
  * Get the value of firstName
  */ 
 public function getFirstName()
 {
  return $this->firstName;
 }

 /**
  * Set the value of firstName
  *
  * @return  self
  */ 
 public function setFirstName($firstName)
 {
  $this->firstName = $firstName;

  return $this;
 }

 /**
  * Get the value of lastName
  */ 
 public function getLastName()
 {
  return $this->lastName;
 }

 /**
  * Set the value of lastName
  *
  * @return  self
  */ 
 public function setLastName($lastName)
 {
  $this->lastName = $lastName;

  return $this;
 }

 /**
  * Get the value of sexe
  */ 
 public function getSexe()
 {
  return $this->sexe;
 }

 /**
  * Set the value of sexe
  *
  * @return  self
  */ 
 public function setSexe($sexe)
 {
  $this->sexe = $sexe;

  return $this;
 }

 /**
  * Get the value of phone
  */ 
 public function getPhone()
 {
  return Productor::normalPhone($this->phone);
 }

 /**
  * Set the value of phone
  *
  * @return  self
  */ 
 public function setPhone($phone)
 {
  $this->phone = $phone;

  return $this;
 }

 /**
  * Get the value of birthday
  */ 
 public function getBirthday()
 {
  return new \DateTime($this->birthday);
 }

 /**
  * Set the value of birthday
  *
  * @return  self
  */ 
 public function setBirthday($birthday)
 {
    #throw new HttpException(400, (new \DateTime($birthday))->getTimestamp());
    $this->birthday = $birthday;

    return $this;
 }

 /**
  * Get the value of nui
  */ 
 public function getNui()
 {
  return $this->nui;
 }

 /**
  * Set the value of nui
  *
  * @return  self
  */ 
 public function setNui($nui)
 {
  $this->nui = $nui;

  return $this;
 }


 /**
  * Get the value of photo
  */ 
 public function getPhoto()
 {
  return $this->photo;
 }

 /**
  * Set the value of photo
  *
  * @return  self
  */ 
 public function setPhoto($photo)
 {
    $this->photo = $photo;

    return $this;
 }

/**
 * Get the value of levelStudy
 */ 
public function getLevelStudy()
{
return $this->levelStudy;
}

/**
 * Set the value of levelStudy
 *
 * @return  self
 */ 
public function setLevelStudy(LevelStudy $levelStudy)
{

    $this->levelStudy = $levelStudy;

    return $this;
}

 /**
  * Get the value of householdSize
  */ 
 public function getHouseholdSize()
 {
  return $this->householdSize;
 }

 /**
  * Set the value of householdSize
  *
  * @return  self
  */ 
 public function setHouseholdSize($householdSize)
 {
  $this->householdSize = (int) $householdSize;

  return $this;
 }

    /**
     * Get the value of maritalState
     *
     * @return  MaritalState
     */ 
    public function getMaritalState()
    {
        return $this->maritalState;
    }

    /**
     * Set the value of maritalState
     *
     * @param  MaritalState  $maritalState
     *
     * @return  self
     */ 
    public function setMaritalState(MaritalState $maritalState)
    {
        $this->maritalState = $maritalState;

        return $this;
    }



    /**
     * Get the value of organization
     *
     * @return  Organization
     */ 
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set the value of organization
     *
     * @param  Organization  $organization
     *
     * @return  self
     */ 
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }
}