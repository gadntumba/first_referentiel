<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductorPreloadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ProductorPreloadRepository::class)]
#[ApiResource]
class ProductorPreload
{

    const CONTACT_REPPORT_DISPUTED_CASE = "Cas litigé";
    const CONTACT_REPPORT_APPOINTMENT_ACCEPTED = "Rendez-vous accepté";
    const CONTACT_REPPORT_POSTPONED_APPOINTMENT = "Rendez-vous reporté";
    const CONTACT_REPPORT_OTHER = "Autres";

    const CONTACT_REPPORTS = [
        self::CONTACT_REPPORT_DISPUTED_CASE, 
        self::CONTACT_REPPORT_APPOINTMENT_ACCEPTED, 
        self::CONTACT_REPPORT_POSTPONED_APPOINTMENT,
        self::CONTACT_REPPORT_OTHER
    ];

    const CONTACT_COMMENTS = [
        null,
        "ENREGISTREE",
        "BENEFICIAIRE DE PADMPME",
        "DOUBLON",
        "FAUX NUMERO",
        "INDISPONIBLE",
        "INJOIGNABLE",
        "MINEURE",
        "NE DECROCHE PAS",
        "NE RECONNAIT PAS LE PROJET",
        "NUMERO DE TELEPHONE VIDE",
        "NUMERO INCORRECT",
        "PAS D'ACTIVITE",
        "PAS DANS LA CIBLE",
        "PAS DE CARTE",
        "PAS DE STRUCTURE",
        "PAS PRESENTE DANS LA VILLE",
        "RDV REPORTE",
        "REFUS D'ENREGISTREMENT",
        "PAS INTERESSE",
        "INDENTITE NON CONFORME",
        "LES NONS NE CORRESPONDENT PAS"
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:productor:preload", "productors:duplicate:read", "productors:assignable:read", "read:productor:level_0"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable:true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:duplicate:read", "productors:assignable:read", "read:productor:level_0"])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable:true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:duplicate:read", "productors:assignable:read", "read:productor:level_0"])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable:true)]
    ##[Groups(["write:productor:preload", "read:productor:preload", "productors:duplicate:read"])]
    ##[Assert\NotBlank(message:"la ville ne peut pas être vide")]
    ##[Assert\NotNull(message:"la ville ne peut pas être vide")]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable:true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:duplicate:read", "productors:assignable:read", "read:productor:level_0"])]
    private ?string $phone1 = null;

    #[ORM\Column(length: 255, nullable:true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:duplicate:read", "productors:assignable:read", "read:productor:level_0"])]
    private ?string $phone2 = null;

    #[ORM\Column(length: 255, nullable:true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:duplicate:read", "read:productor:level_0"])]
    private ?string $phone3 = null;

    #[ORM\Column(length: 255, nullable:true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read", "read:productor:level_0"])]
    #[Assert\NotBlank(message:"la structure ne peut pas être vide")]
    #[Assert\NotNull(message:"la structure ne peut pas être vide")]
    private ?string $structure = null;

    #[ORM\Column(length: 255, nullable:true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:duplicate:read", "productors:assignable:read", "read:productor:level_0"])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read", "read:productor:level_0"])]
    #[Assert\NotBlank(message:"le secteur ne peut pas être vide")]
    #[Assert\NotNull(message:"le secteur ne peut pas être vide")]
    private ?string $sector = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read", "read:productor:level_0"])]
    #[Assert\NotBlank(message:"la commune ne peut pas être vide")]
    #[Assert\NotNull(message:"la commune ne peut pas être vide")]
    private ?string $town = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read", "read:productor:level_0"])]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"la fichier ne peut pas être vide")]
    #[Assert\NotNull(message:"la fichier ne peut pas être vide")]
    #[Groups(["write:productor:preload", "read:productor:preload"])]
    private ?string $fileName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read"])]
    private ?string $quarter = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le normalId ne peut pas être vide")]
    #[Assert\NotNull(message:"le normalId ne peut pas être vide")]
    private ?string $normalId = null;

    #[ORM\OneToMany(mappedBy: 'main', targetEntity: ProductorPreloadDuplicate::class)]
    private Collection $produtorDuplicateMain;

    #[ORM\OneToMany(mappedBy: 'secondary', targetEntity: ProductorPreloadDuplicate::class)]
    private Collection $productorDuplicateSecondaries;

    #[ORM\ManyToOne(inversedBy: 'productorPreloads')]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read"])]
    #[Assert\NotBlank(message:"le normalId ne peut pas être vide")]
    #[Assert\NotNull(message:"le normalId ne peut pas être vide",)]
    private ?City $cityEntity = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read"])]
    private ?string $agentAffect = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read"])]
    private ?string $adminDoAffect = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read"])]
    private ?\DateTimeInterface $affectAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read"])]
    private ?\DateTimeInterface $contactAt = null;

    #[ORM\Column(nullable: true)]
    private ?array $contactsHistory = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read"])]
    private ?string $contactRepport = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["write:productor:preload", "read:productor:preload", "productors:assignable:read"])]
    private ?string $contanctComment = null;

    #[ORM\OneToOne(mappedBy: 'productorPreload', cascade: ['persist', 'remove'])]
    private ?Productor $productor = null;

    public function __construct()
    {
        $this->produtorDuplicateMain = new ArrayCollection();
        $this->productorDuplicateSecondaries = new ArrayCollection();
    }

    public static function hideSpaces(string $text) : string {
        $no_space_string = preg_replace('/\s+/', '', $text);
        return $no_space_string;
    }
    public static function normalPhone(string $text) : string {

        $phone = self::hideSpaces($text);
        $phone = str_replace("+", '', $phone);


        if (empty($phone)) {
            return "";
        }
        //dump(strpos($phone, '243') != "");
        
        if (strpos($phone, '00') === 0) {
            //dump("00");
            $phone = substr($phone, 2);
        }
        
        if (strpos($phone, '243')  === 0) {
            //dump("243");
            $phone = substr($phone, 3);
        }else if (strpos($phone, '0')  === 0) {
            $phone = substr($phone, 1);
        }
        return $phone;
    }

    #[Assert\Callback()]
    public function validatePhoneNumber(ExecutionContextInterface $context)
    {
        if (empty($this->phone1) && empty($this->phone2) && empty($this->phone3)) {
            $context->buildViolation('tu dois au moins renseigner un numro.')
                ->atPath('phone') // Attribut qui reçoit la violation
                ->addViolation();
        }
        $phoneNormal = 0;
        if (!empty($this->phone1)) {

            $phone = self::normalPhone($this->phone1);
            //dd(strlen($phone));
            if (strlen($phone) == 9) {
                $phoneNormal++;
            }
            
        }
        
        if (!empty($this->phone2)) {

            $phone = self::normalPhone($this->phone2);
            //dump($phone);
            if (strlen($phone) == 9) {
                $phoneNormal++;
            }
            
        }
        
        if (!empty($this->phone3)) {

            $phone = self::normalPhone($this->phone3);
            //dump($phone);
            if (strlen($phone) == 9) {
                $phoneNormal++;
            }
            
        }

        //dd($phoneNormal);

        if ($phoneNormal == 0) {
            $context->buildViolation('Tu dois au moins avoir un numéro valide')
                ->atPath('phone') // Attribut qui reçoit la violation
                ->addViolation();
        }
        if ((empty($this->name) && empty($this->firstname)) ||
            (empty($this->firstname) && empty($this->lastname)) ||
             (empty($this->name) && empty($this->lastname))
        ) 
        {
            $context->buildViolation('noms pas conforme : au moins deux noms')
                ->atPath('names') // Attribut qui reçoit la violation
                ->addViolation();            
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function setPhone1(string $phone1): static
    {
        $this->phone1 = $phone1;

        return $this;
    }

    public function getPhone2(): ?string
    {
        return $this->phone2;
    }

    public function setPhone2(string $phone2): static
    {
        $this->phone2 = $phone2;

        return $this;
    }

    public function getPhone3(): ?string
    {
        return $this->phone3;
    }

    public function setPhone3(string $phone3): static
    {
        $this->phone3 = $phone3;

        return $this;
    }

    public function getStructure(): ?string
    {
        return $this->structure;
    }

    public function setStructure(string $structure): static
    {
        $this->structure = $structure;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): static
    {
        $this->sector = $sector;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(?string $town): static
    {
        $this->town = $town;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getQuarter(): ?string
    {
        return $this->quarter;
    }

    public function setQuarter(?string $quarter): static
    {
        $this->quarter = $quarter;

        return $this;
    }

    public function getNormalId(): ?string
    {
        return $this->normalId;
    }

    public function setNormalId(): static
    {
        $this->normalId = $this->name . $this->firstname . $this->lastname . $this->city . $this->town . $this->quarter. $this->address. $this->sector. $this->phone1. $this->phone2. $this->phone3. $this->structure;

        return $this;
    }

    /**
     * @return Collection<int, ProductorPreloadDuplicate>
     */
    public function getProdutorDuplicateMain(): Collection
    {
        return $this->produtorDuplicateMain;
    }

    public function addProdutorDuplicateMain(ProductorPreloadDuplicate $produtorDuplicateMain): static
    {
        if (!$this->produtorDuplicateMain->contains($produtorDuplicateMain)) {
            $this->produtorDuplicateMain->add($produtorDuplicateMain);
            $produtorDuplicateMain->setMain($this);
        }

        return $this;
    }

    public function removeProdutorDuplicateMain(ProductorPreloadDuplicate $produtorDuplicateMain): static
    {
        if ($this->produtorDuplicateMain->removeElement($produtorDuplicateMain)) {
            // set the owning side to null (unless already changed)
            if ($produtorDuplicateMain->getMain() === $this) {
                $produtorDuplicateMain->setMain(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductorPreloadDuplicate>
     */
    public function getProductorDuplicateSecondaries(): Collection
    {
        return $this->productorDuplicateSecondaries;
    }

    public function addProductorDuplicateSecondary(ProductorPreloadDuplicate $productorDuplicateSecondary): static
    {
        if (!$this->productorDuplicateSecondaries->contains($productorDuplicateSecondary)) {
            $this->productorDuplicateSecondaries->add($productorDuplicateSecondary);
            $productorDuplicateSecondary->setSecondary($this);
        }

        return $this;
    }

    public function removeProductorDuplicateSecondary(ProductorPreloadDuplicate $productorDuplicateSecondary): static
    {
        if ($this->productorDuplicateSecondaries->removeElement($productorDuplicateSecondary)) {
            // set the owning side to null (unless already changed)
            if ($productorDuplicateSecondary->getSecondary() === $this) {
                $productorDuplicateSecondary->setSecondary(null);
            }
        }

        return $this;
    }

    /*public function getCityEntity(): ?City
    {
        return $this->cityEntity;
    }

    public function setCityEntity(?City $cityEntity): static
    {
        $this->cityEntity = $cityEntity;

        return $this;
    }

    public function getProductor(): ?Productor
    {
        return $this->productor;
    }

    public function setProductor(?Productor $productor): static
    {
        $this->productor = $productor;

        return $this;
    }*/

    public function getAgentAffect(): ?string
    {
        return $this->agentAffect;
    }

    public function setAgentAffect(?string $agentAffect): static
    {
        $this->agentAffect = $agentAffect;

        return $this;
    }

    public function getAdminDoAffect(): ?string
    {
        return $this->adminDoAffect;
    }

    public function setAdminDoAffect(?string $adminDoAffect): static
    {
        $this->adminDoAffect = $adminDoAffect;

        return $this;
    }

    public function getAffectAt(): ?\DateTimeInterface
    {
        return $this->affectAt;
    }

    public function setAffectAt(?\DateTimeInterface $affectAt): static
    {
        $this->affectAt = $affectAt;

        return $this;
    }

    public function getContactAt(): ?\DateTimeInterface
    {
        return $this->contactAt;
    }

    public function setContactAt(?\DateTimeInterface $contactAt): static
    {
        $this->contactAt = $contactAt;

        return $this;
    }

    public function getContactsHistory(): ?array
    {
        return $this->contactsHistory;
    }

    public function setContactsHistory(array $contactsHistory): static
    {
        $this->contactsHistory = $contactsHistory;

        return $this;
    }

    public function getContactRepport(): ?string
    {
        return $this->contactRepport;
    }

    public function setContactRepport(?string $contactRepport): static
    {
        $this->contactRepport = $contactRepport;

        return $this;
    }

    public function getContanctComment(): ?string
    {
        return $this->contanctComment;
    }

    public function setContanctComment(?string $contanctComment): static
    {
        $this->contanctComment = $contanctComment;

        return $this;
    }

    public function getCityEntity(): ?City
    {
        return $this->cityEntity;
    }

    public function setCityEntity(?City $cityEntity): static
    {
        $this->cityEntity = $cityEntity;

        return $this;
    }

    public function getProductor(): ?Productor
    {
        return $this->productor;
    }

    public function setProductor(?Productor $productor): static
    {
        // unset the owning side of the relation if necessary
        if ($productor === null && $this->productor !== null) {
            $this->productor->setProductorPreload(null);
        }

        // set the owning side of the relation if necessary
        if ($productor !== null && $productor->getProductorPreload() !== $this) {
            $productor->setProductorPreload($this);
        }

        $this->productor = $productor;

        return $this;
    }
}
