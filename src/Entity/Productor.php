<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ProductorRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=ProductorRepository::class)
 * 
 */
class Productor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"read:productor:level_0"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:personnal_id_data","read:collection","write:Productor"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:item","read:collection"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:personnal_id_data","read:item","read:collection","write:Productor"})
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection"})
     */
    private $birthdate;

    /**
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *  min = 9,
     *  max = 9 
     *)
     * @Assert\Regex(
     *      pattern="/\+/",
     *      match=false,
     *      message="Your nui cannot contain a letter"
     * )
     */
    private $nui;

    /**
     * @ORM\ManyToOne(targetEntity=LevelStudy::class, inversedBy="productors")
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection"})
     */
    private $levelStudy;

    /**
     * @ORM\OneToMany(targetEntity=AgriculturalActivity::class, mappedBy="productor")
     * @Groups({"read:productor:activities_data","read:collection","write:Productor"})
     */
    private $AgriculturalActivity;

    /**
     * @ORM\OneToMany(targetEntity=NFC::class, mappedBy="productor")
     */
    private $nfc;

    /**
     * @ORM\OneToMany(targetEntity=FichingActivity::class, mappedBy="productor")
     * @Groups({"read:productor:activities_data","read:collection","write:Productor"})
     */
    private $fichingactivity;

    /**
     * @ORM\OneToMany(targetEntity=StockRaisingActivity::class, mappedBy="productor")
     * @Groups({"read:productor:activities_data","read:collection","write:Productor"})
     */
    private $raisingactivity;

    /**
     * @ORM\ManyToOne(targetEntity=HouseKeeping::class, inversedBy="productors")
     * @Groups({"read:productor:house_keeping","read:collection","write:Productor"})
     */
    private $housekeeping;

    /**
     * @ORM\ManyToMany(targetEntity=Smartphone::class, inversedBy="productors")
     */
    private $smartphone;

    /**
     * @ORM\ManyToOne(targetEntity=Monitor::class, inversedBy="productors")
     * @Groups({"read:collection","write:Productor"})
     */
    private $monitor;

    /**
     * @ORM\Column(type="float")
     * @Groups({"read:productor:level_0","read:collection","write:Productor"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     * @Groups({"read:productor:level_0","read:collection","write:Productor"})
     */
    private $longitude;

    /**
     * @ORM\Column(type="float")
     * @Groups({"read:productor:level_0","read:collection","write:Productor"})
     */
    private $altitude;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:piece_of_id_data","read:collection","write:Productor"})
     */
    private $numberPieceOfIdentification;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"read:collection","write:Productor"})
     */
    private $photoPieceOfIdentification;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:personnal_id_data","read:collection","write:Productor"})
     */
    private $householdSize;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"read:collection","write:Productor"})
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"read:collection","write:Productor"})
     */
    private $incumbentPhoto;

    /**
     * @Groups({"read:productor:piece_of_id_data"})
     * @ORM\ManyToOne(targetEntity=PieceIdentificationType::class, inversedBy="productors")
     */
    private $typePieceOfIdentification;

    public function __construct()
    {
        $this->AgriculturalActivity = new ArrayCollection();
        $this->nfc = new ArrayCollection();
        $this->fichingactivity = new ArrayCollection();
        $this->raisingactivity = new ArrayCollection();
        $this->smartphone = new ArrayCollection();
    }

    public static function validationGroups(self $productor){
        return ['create:Productor'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getNui(): ?string
    {
        return $this->nui;
    }

    public function setNui(string $nui): self
    {
        $this->nui = $nui;

        return $this;
    }

    public function getLevelStudy(): ?LevelStudy
    {
        return $this->levelStudy;
    }

    public function setLevelStudy(?LevelStudy $levelStudy): self
    {
        $this->levelStudy = $levelStudy;

        return $this;
    }

    /**
     * @return Collection|AgriculturalActivity[]
     */
    public function getAgriculturalActivity(): Collection
    {
        return $this->AgriculturalActivity;
    }

    public function addAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if (!$this->AgriculturalActivity->contains($agriculturalActivity)) {
            $this->AgriculturalActivity[] = $agriculturalActivity;
            $agriculturalActivity->setProductor($this);
        }

        return $this;
    }

    public function removeAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if ($this->AgriculturalActivity->removeElement($agriculturalActivity)) {
            // set the owning side to null (unless already changed)
            if ($agriculturalActivity->getProductor() === $this) {
                $agriculturalActivity->setProductor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NFC[]
     */
    public function getNfc(): Collection
    {
        return $this->nfc;
    }

    public function addNfc(NFC $nfc): self
    {
        if (!$this->nfc->contains($nfc)) {
            $this->nfc[] = $nfc;
            $nfc->setProductor($this);
        }

        return $this;
    }

    public function removeNfc(NFC $nfc): self
    {
        if ($this->nfc->removeElement($nfc)) {
            // set the owning side to null (unless already changed)
            if ($nfc->getProductor() === $this) {
                $nfc->setProductor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FichingActivity[]
     */
    public function getFichingactivity(): Collection
    {
        return $this->fichingactivity;
    }

    public function addFichingactivity(FichingActivity $fichingactivity): self
    {
        if (!$this->fichingactivity->contains($fichingactivity)) {
            $this->fichingactivity[] = $fichingactivity;
            $fichingactivity->setProductor($this);
        }

        return $this;
    }

    public function removeFichingactivity(FichingActivity $fichingactivity): self
    {
        if ($this->fichingactivity->removeElement($fichingactivity)) {
            // set the owning side to null (unless already changed)
            if ($fichingactivity->getProductor() === $this) {
                $fichingactivity->setProductor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StockRaisingActivity[]
     */
    public function getRaisingactivity(): Collection
    {
        return $this->raisingactivity;
    }

    public function addRaisingactivity(StockRaisingActivity $raisingactivity): self
    {
        if (!$this->raisingactivity->contains($raisingactivity)) {
            $this->raisingactivity[] = $raisingactivity;
            $raisingactivity->setProductor($this);
        }

        return $this;
    }

    public function removeRaisingactivity(StockRaisingActivity $raisingactivity): self
    {
        if ($this->raisingactivity->removeElement($raisingactivity)) {
            // set the owning side to null (unless already changed)
            if ($raisingactivity->getProductor() === $this) {
                $raisingactivity->setProductor(null);
            }
        }

        return $this;
    }

    public function getHousekeeping(): ?HouseKeeping
    {
        return $this->housekeeping;
    }

    public function setHousekeeping(?HouseKeeping $housekeeping): self
    {
        $this->housekeeping = $housekeeping;

        return $this;
    }

    /**
     * @return Collection|Smartphone[]
     */
    public function getSmartphone(): Collection
    {
        return $this->smartphone;
    }

    public function addSmartphone(Smartphone $smartphone): self
    {
        if (!$this->smartphone->contains($smartphone)) {
            $this->smartphone[] = $smartphone;
        }

        return $this;
    }

    public function removeSmartphone(Smartphone $smartphone): self
    {
        $this->smartphone->removeElement($smartphone);

        return $this;
    }

    public function getMonitor(): ?Monitor
    {
        return $this->monitor;
    }

    public function setMonitor(?Monitor $monitor): self
    {
        $this->monitor = $monitor;

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

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

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


    public function getNumberPieceOfIdentification(): ?string
    {
        return $this->numberPieceOfIdentification;
    }

    public function setNumberPieceOfIdentification(string $numberPieceOfIdentification): self
    {
        $this->numberPieceOfIdentification = $numberPieceOfIdentification;

        return $this;
    }

    public function getPhotoPieceOfIdentification(): ?string
    {
        return $this->photoPieceOfIdentification;
    }

    public function setPhotoPieceOfIdentification(string $photoPieceOfIdentification): self
    {
        $this->photoPieceOfIdentification = $photoPieceOfIdentification;

        return $this;
    }

    public function getHouseholdSize(): ?int
    {
        return $this->householdSize;
    }

    public function setHouseholdSize(int $householdSize): self
    {
        $this->householdSize = $householdSize;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getIncumbentPhoto()
    {
        return $this->incumbentPhoto;
    }

    public function setIncumbentPhoto($incumbentPhoto): self
    {
        $this->incumbentPhoto = $incumbentPhoto;

        return $this;
    }

    public function getTypePieceOfIdentification(): ?PieceIdentificationType
    {
        return $this->typePieceOfIdentification;
    }

    public function setTypePieceOfIdentification(?PieceIdentificationType $typePieceOfIdentification): self
    {
        $this->typePieceOfIdentification = $typePieceOfIdentification;

        return $this;
    }
}
