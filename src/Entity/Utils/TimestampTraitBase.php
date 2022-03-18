<?php

namespace App\Entity\Utils;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;


trait TimestampTraitBase{

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(["timestamp:read"])]
    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(["timestamp:read"])]
    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(["timestamp:read"])]
    #[ORM\Column(type: 'datetime', nullable:true)]
    private $deletedAt;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     * 
     */
    #[Groups(["slugger:read"])]
    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function getSlug()
    {
        return $this->slug;
    }

}