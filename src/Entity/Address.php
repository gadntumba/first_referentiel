<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *@ORM\Entity(repositoryClass=AddressRepository::class)
 *
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"read:adresscollection"})
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:house_keeping"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:house_keeping","read:adresscollection"})
     * @Assert\NotNull
     * @Assert\Type("string")
     */
    private $line;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"read:adresscollection"})
     * 
     * @Assert\Type("float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     * 
     * @Assert\Type("float")
     * @Groups({"read:adresscollection"})
     */
    private $longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     * 
     * @Assert\Type("float")
     * @Groups({"read:adresscollection"})
     */
    private $altitude;

    /**
     * @ORM\OneToMany(targetEntity=AgriculturalActivity::class, mappedBy="adress")
     */
    private $agriculturalActivities;

    /**
     * @ORM\OneToOne(targetEntity=StockRaisingActivity::class, cascade={"persist", "remove"})
     */
    private $stockRaisInActivity;

    /**
     * @ORM\OneToOne(targetEntity=HouseKeeping::class, cascade={"persist", "remove"})
     */
    private $houseKeeping;

    /**
     * @ORM\ManyToOne(targetEntity=Town::class, inversedBy="addresses")
     * @Groups({"read:productor:house_keeping","read:adresscollection"})
     */
    private $town;

    /**
     * @ORM\ManyToOne(targetEntity=Sector::class, inversedBy="addresses")
     * @Groups({"read:productor:house_keeping","read:adresscollection"})
     */
    private $sector;

    public function __construct()
    {
        $this->agriculturalActivities = new ArrayCollection();
    }

    /*
    * @Groups({"read:adresscollection"})
    */
    public function getIri(): int
    {
        return ''. $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLine(): ?string
    {
        return $this->line;
    }

    public function setLine(string $line): self
    {
        $this->line = $line;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = (float) $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = (float) $longitude;

        return $this;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function setAltitude(string $altitude): self
    {
        $this->altitude = (float) $altitude;

        return $this;
    }

    /**
     * @return Collection|AgriculturalActivity[]
     */
    public function getAgriculturalActivities(): Collection
    {
        return $this->agriculturalActivities;
    }

    public function addAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if (!$this->agriculturalActivities->contains($agriculturalActivity)) {
            $this->agriculturalActivities[] = $agriculturalActivity;
            $agriculturalActivity->setAdress($this);
        }

        return $this;
    }

    public function removeAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if ($this->agriculturalActivities->removeElement($agriculturalActivity)) {
            // set the owning side to null (unless already changed)
            if ($agriculturalActivity->getAdress() === $this) {
                $agriculturalActivity->setAdress(null);
            }
        }

        return $this;
    }

    public function getStockRaisInActivity(): ?StockRaisingActivity
    {
        return $this->stockRaisInActivity;
    }

    public function setStockRaisInActivity(?StockRaisingActivity $stockRaisInActivity): self
    {
        $this->stockRaisInActivity = $stockRaisInActivity;

        return $this;
    }

    public function getHouseKeeping(): ?HouseKeeping
    {
        return $this->houseKeeping;
    }

    public function setHouseKeeping(?HouseKeeping $houseKeeping): self
    {
        $this->houseKeeping = $houseKeeping;

        return $this;
    }

    public function getTown(): ?Town
    {
        return $this->town;
    }

    public function setTown(?Town $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getSector(): ?Sector
    {
        return $this->sector;
    }

    public function setSector(?Sector $sector): self
    {
        $this->sector = $sector;

        return $this;
    }
}
