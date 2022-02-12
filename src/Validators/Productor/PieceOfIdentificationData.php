<?php
namespace App\Validators\Productor;

use App\Entity\PieceIdentificationType;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;


class PieceOfIdentificationData {
    private $pieceIdentificationType; //PieceIdentificationType
    /**
     * @var string
    */
    private $pieceId; //String
    /**
     * 
     */
    private $photo; //String
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer; //int

    
    public function __construct(DenormalizerInterface $denormalizer)
    {
        $this->denormalizer = $denormalizer;
    }


    /**
     * Get the value of pieceIdentificationType
     */ 
    public function getPieceIdentificationType()
    {
        return $this->pieceIdentificationType;
    }

    /**
     * Set the value of pieceIdentificationType
     *
     * @return  self
     */ 
    public function setPieceIdentificationType(array $pieceIdentificationType)
    {
        $pieceIdentificationType = $this->denormalizer->denormalize($pieceIdentificationType, PieceIdentificationType::class, null, []);
    
        if (!($pieceIdentificationType instanceof PieceIdentificationType)) {
            throw new \Exception("Not supported yet");
        }
    
        $this->pieceIdentificationType = $pieceIdentificationType;

        return $this;
    }

    /**
     * Get the value of pieceId
     */ 
    public function getPieceId()
    {
        return $this->pieceId;
    }

    /**
     * Set the value of pieceId
     *
     * @return  self
     */ 
    public function setPieceId($pieceId)
    {
        $this->pieceId = $pieceId;

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
}