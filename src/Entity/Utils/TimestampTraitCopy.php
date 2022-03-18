<?php

namespace App\Entity\Utils;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;

trait TimestampTraitCopy{

    use TimestampTraitBase;

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        //dd($id);
        $this->id = $id;

        return $this;
    }
    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt(\DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

     /**
     * Set the value of updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt(\DateTimeInterface $updateAt = null)
    {
        $this->updatedAt = $updateAt;

        return $this;
    }

    /**
     * Set the value of deletedAt
     * @return  self
     */
    public function setDeletedAt(\DateTimeInterface $deletedAt = null)
    {
        $this->deletedAt =  $deletedAt;

        return $this;
    }

    /**
     * 
     * @return  self
     */
    public function setSlug(string $uuid)
    {
        $this->slug = $uuid;
        return $this;
    }
}