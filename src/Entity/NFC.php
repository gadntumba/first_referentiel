<?php

namespace App\Entity;

use App\Repository\NFCRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * 
 */
#[ORM\Entity(repositoryClass:NFCRepository::class)]
class NFC
{
    /**
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    /**
     * 
     */
    #[ORM\Column(type:"json")]
    private $metadata = [];

    /**
     * 
     */
    #[ORM\ManyToOne(targetEntity:Productor::class, inversedBy:"nfc")]
    private $productor;

    /**
     * 
     */
    #[ORM\Column(type:"date")]
    private $createdAt;

    /**
     * 
     */
    #[ORM\Column(type:"float")]
    private $longitude;

    /**
     */
    #[ORM\Column(type:"float")]
    private $latitude;

    /**
     */
    #[ORM\Column(type:"float")]
    private $altitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getProductor(): ?Productor
    {
        return $this->productor;
    }

    public function setProductor(?Productor $productor): self
    {
        $this->productor = $productor;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function setAltitude(float $altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }
}
