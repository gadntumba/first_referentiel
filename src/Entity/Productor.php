<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ProductorRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Utils\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Mink67\KafkaConnect\Annotations\Copyable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Mink67\MultiPartDeserialize\Annotations\File;
use Mink67\MultiPartDeserialize\Services\NormaliserFile\LiipImagineNormalizerFile;

//#[Copyable(resourceName: 'producer.producer', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
#[ORM\Entity(repositoryClass:ProductorRepository::class)]
#[ORM\HasLifecycleCallbacks()]
/**
 * 
 * 
 * @UniqueEntity(
 *     fields= "phoneNumber",
 *     errorPath="phoneNumber",
 *     message="Ce numéro de téléphone existe déjà"
 * )
 * @UniqueEntity(
 *     fields= "nui",
 *     errorPath="nui",
 *     message="Ce NUI existe déjà"
 * )
 */
class Productor
{
    use TimestampTrait;
    const GENRES = ['M', 'F'];
    /**
     * 
     * 
     * @Groups({"read:productor:level_0", "event:kafka", "read:observ"})
     * 
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    /**
     * 
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection", "read:observ"})
     */
    #[ORM\Column(type:"string", length:255)]
    private $name;

    /**
     * 
     * @Groups({"read:productor:personnal_id_data","read:collection","write:Productor", "read:observ"})
     */
    #[ORM\Column(type:"string", length:255)]
    private $firstName;

    /**
     * 
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:item","read:collection", "read:observ"})
     */
    #[ORM\Column(type:"string", length:255)]
    private $lastName;

    #[Assert\Choice(choices: Productor::GENRES, message: 'Choisir le genre valid.')]
    /**
     * 
     * @Groups({"read:productor:personnal_id_data","read:item","read:collection","write:Productor", "read:observ"})
     * @Assert\Choice(choices=Productor::GENRES)
     */
    #[ORM\Column(type:"string", length:255)]
    private $sexe;

    /**
     * 
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection", "read:observ"})
     * @Assert\Length(
     *  min = 10,
     *  max = 10 
     *)
     * @Assert\Regex(
     *      pattern="/\d+/",
     *      message="Votre Nnuméro de téléphone doit contenir dix chiffres"
     * )
     */
    #[ORM\Column(type:"string", length:255)]
    private $phoneNumber;

    /**
     * 
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection"})
     */
    #[ORM\Column(type:"date")]
    private $birthdate;

    /**
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection"})
     * 
     * @Assert\Length(
     *  min = 9,
     *  max = 9 
     *)
     * @Assert\Regex(
     *      pattern="/\d+/",
     *      message="Votre NUI ne peut pas contenir des lettres"
     * )
     */
    #[ORM\Column(type:"string", length:255)]
    private $nui;

    /**
     * 
     * @Groups({"read:productor:personnal_id_data","write:Productor","read:collection"})
     */
    #[ORM\ManyToOne(targetEntity:LevelStudy::class, inversedBy:"productors")]
    private $levelStudy;

    /**
     * 
     * @Groups({"read:productor:activities_data","read:collection","write:Productor"})
     */
    #[ORM\OneToMany(targetEntity:AgriculturalActivity::class, mappedBy:"productor")]
    private $AgriculturalActivity;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:NFC::class, mappedBy:"productor")]
    private $nfc;

    /**
     * 
     * @Groups({"read:productor:activities_data","read:collection","write:Productor"})
     */
    #[ORM\OneToMany(targetEntity:FichingActivity::class, mappedBy:"productor")]
    private $fichingactivity;

    /**
     * 
     * @Groups({"read:productor:activities_data","read:collection","write:Productor"})
     */
    #[ORM\OneToMany(targetEntity:StockRaisingActivity::class, mappedBy:"productor")]
    private $raisingactivity;

    /**
     * 
     * @Groups({"read:productor:house_keeping","read:collection","write:Productor"})
     */
    #[ORM\ManyToOne(targetEntity:HouseKeeping::class, inversedBy:"productors")]
    private $housekeeping;

    /**
     * 
     */
    #[ORM\ManyToMany(targetEntity:Smartphone::class, inversedBy:"productors")]
    private $smartphone;

    /**
     * 
     * @Groups({"read:collection","write:Productor"})
     */
    #[ORM\ManyToOne(targetEntity:Monitor::class, inversedBy:"productors")]
    private $monitor;

    /**
     * 
     * @Groups({"read:productor:level_0","read:collection","write:Productor"})
     */
    #[ORM\Column(type:"float")]
    private $latitude;

    /**
     * 
     * @Groups({"read:productor:level_0","read:collection","write:Productor"})
     */
    #[ORM\Column(type:"float")]
    private $longitude;

    /**
     * 
     * @Groups({"read:productor:level_0","read:collection","write:Productor"})
     */
    #[ORM\Column(type:"float")]
    private $altitude;


    /**
     * 
     * @Groups({"read:productor:piece_of_id_data","read:collection","write:Productor"})
     */
    #[ORM\Column(type:"string", length:255)]
    private $numberPieceOfIdentification;

    /**
     * 
     * @Groups({"read:collection","read:producer:image","write:Productor"})
     */
    #[ORM\Column(type:"string")]
    #[File(normalizerClassName: LiipImagineNormalizerFile::class, normalizerParams:["filter" => ["pic_identity"]])]
    private $photoPieceOfIdentification;

    /**
     * 
     * @Groups({"read:productor:personnal_id_data","read:collection","write:Productor"})
     */
    #[ORM\Column(type:"integer")]
    private $householdSize;

    /**
     * 
     * @Groups({"read:collection","write:Productor"})
     */
    #[ORM\Column(type:"date", nullable:true)]
    private $deletedAt;

    /**
     * 
     * @Groups({"read:collection","read:producer:image","write:Productor"})
     */
    #[ORM\Column(type:"string")]
    #[File(normalizerClassName: LiipImagineNormalizerFile::class, normalizerParams:["filter" => ["pic_producer"]])]
    private $incumbentPhoto;

    /**
     * @Groups({"read:productor:piece_of_id_data"})
     * 
     */
    #[ORM\ManyToOne(targetEntity:PieceIdentificationType::class, inversedBy:"productors")]
    private $typePieceOfIdentification;

    #[ORM\Column(length: 255)]
    private ?string $investigatorId = null;
    /**
     * @Groups({"read:productor:activities_data","read:collection","write:Productor", "read:producer:document"})
     * 
     */
    #[ORM\OneToMany(mappedBy: 'productor', targetEntity: EntrepreneurialActivity::class)]
    private Collection $entrepreneurialActivities;

    #[ORM\Column(nullable: true)]
    private ?int $remoteId = null;

    #[ORM\Column(nullable:true)]
    private ?int $returnStatusCode = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $returnMessage = null;

    #[ORM\ManyToOne(inversedBy: 'productors')]
    private ?MaritalState $maritalState = null;
    /**
     * @Groups({"read:productor:personnal_id_data","read:collection","write:Productor"})
     * 
     */
    #[ORM\ManyToOne(inversedBy: 'productors', cascade:['persist'])]
    private ?Organization $organization = null;
    /**
     * 
     * @Groups({"read:productor:level_0"})
     */
    #[ORM\Column(nullable: true)]
    private ?bool $isActive = false;

    #[ORM\Column(nullable: true)]
    private ?bool $isNormal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $validatorId = null;

    #[ORM\ManyToOne(inversedBy: 'productors')]
    /**
     * 
     * @Groups({"read:productor:level_0"})
     */
    private ?Instigator $instigator = null;

    #[ORM\OneToMany(mappedBy: 'productor', targetEntity: Observation::class)]
    private Collection $observations;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    /**
     * 
     * @Groups({"read:productor:level_0"})
     */
    private ?\DateTimeInterface $validateAt = null;

    #[ORM\Column(nullable: true)]
    /**
     * 
     * @Groups({"read:productor:level_0"})
     */
    private ?array $feedBack = null;

    public function __construct()
    {
        $this->AgriculturalActivity = new ArrayCollection();
        $this->nfc = new ArrayCollection();
        $this->fichingactivity = new ArrayCollection();
        $this->raisingactivity = new ArrayCollection();
        $this->smartphone = new ArrayCollection();
        $this->entrepreneurialActivities = new ArrayCollection();
        $this->observations = new ArrayCollection();
        
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

    public function getInvestigatorId(): ?string
    {
        return $this->investigatorId;
    }

    public function setInvestigatorId(string $investigatorId): self
    {
        $this->investigatorId = $investigatorId;

        return $this;
    }

    /**
     * @return Collection<int, EntrepreneurialActivity>
     */
    public function getEntrepreneurialActivities(): Collection
    {
        return $this->entrepreneurialActivities;
    }

    public function addEntrepreneurialActivity(EntrepreneurialActivity $entrepreneurialActivity): static
    {
        if (!$this->entrepreneurialActivities->contains($entrepreneurialActivity)) {
            $this->entrepreneurialActivities->add($entrepreneurialActivity);
            $entrepreneurialActivity->setProductor($this);
        }

        return $this;
    }

    public function removeEntrepreneurialActivity(EntrepreneurialActivity $entrepreneurialActivity): static
    {
        if ($this->entrepreneurialActivities->removeElement($entrepreneurialActivity)) {
            // set the owning side to null (unless already changed)
            if ($entrepreneurialActivity->getProductor() === $this) {
                $entrepreneurialActivity->setProductor(null);
            }
        }

        return $this;
    }

    public function getRemoteId(): ?int
    {
        return $this->remoteId;
    }

    public function setRemoteId(?int $remoteId): static
    {
        $this->remoteId = $remoteId;

        return $this;
    }

    public function getReturnStatusCode(): ?int
    {
        return $this->returnStatusCode;
    }

    public function setReturnStatusCode(int $returnStatusCode): static
    {
        $this->returnStatusCode = $returnStatusCode;

        return $this;
    }

    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    public function setReturnMessage(string $returnMessage): static
    {
        $this->returnMessage = $returnMessage;

        return $this;
    }

    public function getMaritalState(): ?MaritalState
    {
        return $this->maritalState;
    }

    public function setMaritalState(?MaritalState $maritalState): static
    {
        $this->maritalState = $maritalState;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isIsNormal(): ?bool
    {
        return $this->isNormal;
    }

    public function setIsNormal(?bool $isNormal): static
    {
        $this->isNormal = $isNormal;

        return $this;
    }

    public function getValidatorId(): ?string
    {
        return $this->validatorId;
    }

    public function setValidatorId(?string $validatorId): static
    {
        $this->validatorId = $validatorId;

        return $this;
    }

    public function getInstigator(): ?Instigator
    {
        return $this->instigator;
    }

    public function setInstigator(?Instigator $instigator): static
    {
        $this->instigator = $instigator;

        return $this;
    }

    /**
     * @return Collection<int, Observation>
     */
    public function getObservations(): Collection
    {
        return $this->observations;
    }

    public function addObservation(Observation $observation): static
    {
        if (!$this->observations->contains($observation)) {
            $this->observations->add($observation);
            $observation->setProductor($this);
        }

        return $this;
    }

    public function removeObservation(Observation $observation): static
    {
        if ($this->observations->removeElement($observation)) {
            // set the owning side to null (unless already changed)
            if ($observation->getProductor() === $this) {
                $observation->setProductor(null);
            }
        }

        return $this;
    }

    public function getValidateAt(): ?\DateTimeInterface
    {
        return $this->validateAt;
    }

    public function setValidateAt(?\DateTimeInterface $validateAt): static
    {
        $this->validateAt = $validateAt;

        return $this;
    }

    public function getFeedBack(): ?array
    {
        return $this->feedBack;
    }

    public function setFeedBack(?array $feedBack): static
    {
        $this->feedBack = $feedBack;

        return $this;
    }
}
