<?php

namespace App\Entity\Utils;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;

trait TimestampTrait{

    use TimestampTraitBase;
    
    /**
     * Set the value of createdAt
     *@ORM\PrePersist
     * @return  self
     */ 
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();

        //dd($this->createdAt);

        return $this;
    }

     /**
     * Set the value of updatedAt
     *@ORM\PrePersist
     *@ORM\PreUpdate
     *
     * @return  self
     */ 
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * Set the value of deletedAt
     * @ORM\PreRemove
     * @return  self
     */
    public function setDeletedAt()
    {
        $this->deletedAt =  new \DateTime();

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @return  self
     */
    public function setSlug()
    {
        $uuid = Uuid::v6();
        $this->slug = (string) $uuid;
        return $this;
    }
}