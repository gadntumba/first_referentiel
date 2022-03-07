<?php

namespace App\Entity\Utils;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TimestampTrait{

    
    /**
     *@ORM\Column(type="datetime", nullable=true)
     *@Groups({"timestamp:read"})
     */
    
    private $createdAt;

     /**
      *@ORM\Column(type="datetime", nullable=true)
      *@Groups({"timestamp:read"})
      */
    
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     */

    private $deletedAt;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Groups({"slug:read"})
     */
     
    private $slug;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     * @ORM\PrePersist
     */ 

    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

      /**
     * Set the value of updatedAt
     *
     * @return  self
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

     /**
     * Set the value of deletedAt
     * @return  self
     * @ORM\PreRemove
     */ 
    public function setDeletedAt()
    {
        $this->deletedAt =  new \DateTime();

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @ORM\PrePersist
     * @return  self
     */
    public function setSlug()
    {
        $this->slug = (string) Uuid::v6();

        return $this;
    }
}