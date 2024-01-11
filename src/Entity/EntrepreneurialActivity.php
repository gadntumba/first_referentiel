<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\EntrepreneurialActivity\Document;
use App\Entity\EntrepreneurialActivity\LegalStatus;
use App\Entity\EntrepreneurialActivity\ProductDisplayMode;
use App\Entity\EntrepreneurialActivity\TurnoverRange;
use App\Repository\EntrepreneurialActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EntrepreneurialActivityRepository::class)]
#[ApiResource]
class EntrepreneurialActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Type("int")]
    #[Assert\Positive()]
    #[Assert\GreaterThan(1700)]
    private $creationYear = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $isRegistered = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $haveConstitutiveAct = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $haveInternalRegulations = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $haveAdministrationProceduresManual = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $haveFinanceProceduresManual = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $haveManagementConsultancy = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $haveAccounting = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    #[Assert\Choice(options:["RCCM","F92"])]
    private ?string $documentType = null;

    private $documentPhoto = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Type("int")]
    #[Assert\PositiveOrZero()]
    private $countVolunteerStaff = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Type("int")]
    #[Assert\PositiveOrZero()]
    private $countStaffPaid = null;

    #[ORM\Column(nullable:true)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("int")]
    private $yearFirstTaxPayment = null;

    #[ORM\Column(nullable:true)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private ?string $taxeNames = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private $taxeAmount = null;

    #[ORM\ManyToOne(inversedBy: 'entrepreneurialActivities')]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private ?TaxePayMode $taxePayMode = null;

    #[ORM\Column(nullable:true)]
    ##[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("float")]
    ##[Assert\PositiveOrZero()]
    private $amountPaidDay = null;

    #[ORM\Column(nullable:true)]
    ##[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("float")]
    ##[Assert\PositiveOrZero()]
    private $amountPaidMonth = null;

    #[ORM\Column(nullable:true)]
    ##[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("float")]
    ##[Assert\PositiveOrZero()]
    private  $amountPaidQuarter = null;

    #[ORM\Column(nullable: true)]
    ##[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("float")]
    ##[Assert\PositiveOrZero()]
    private $amountPaidSemester = null;

    #[ORM\Column(nullable:true)]
    ##[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("float")]
    ##[Assert\PositiveOrZero()]
    private $amountPaidAnnually = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $useMobileBank = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $useCommercialBank = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotNull()]
    private $useMicrofinance = null;

    #[ORM\Column(length: 255, nullable:true)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private ?string $addressLine = null;

    #[ORM\ManyToOne(inversedBy: 'entrepreneurialActivities')]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private ?Town $town = null;

    #[ORM\ManyToOne(inversedBy: 'entrepreneurialActivities')]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private ?Territorry $territory = null;

    #[ORM\ManyToOne(inversedBy: 'entrepreneurialActivities')]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Valid()]
    private ?TurnoverRange $turnover = null;

    #[ORM\ManyToOne(inversedBy: 'entrepreneurialActivities')]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private ?LegalStatus $legalStatus = null;

    #[ORM\ManyToOne(inversedBy: 'entrepreneurialActivities')]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private ?ProductDisplayMode $productDisplayMode = null;

    #[ORM\ManyToOne(inversedBy: 'entrepreneurialActivities')]
    private ?Productor $productor = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $documentPath = null;

    #[ORM\Column(nullable: true)]
    private ?array $taxes = null;

    #[ORM\Column(nullable: true)]
    private ?array $activities = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Type("int")]
    #[Assert\Positive()]
    #[Assert\GreaterThan(1700)]
    private $yearOfLegalization = null;
    
    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: Document::class)]
    #[Groups(["read:producer:document"])]
    private Collection $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
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

    public function getCreationYear(): ?int
    {
        return $this->creationYear;
    }

    public function setCreationYear($creationYear): static
    {
        $this->creationYear = (int) $creationYear;

        return $this;
    }

    public function isIsRegistered(): ?bool
    {
        return $this->isRegistered;
    }

    public function setIsRegistered($isRegistered): static
    {
        //dd((bool) $isRegistered);
        $this->isRegistered = (bool) $isRegistered ;

        return $this;
    }

    public function isHaveConstitutiveAct(): ?bool
    {
        return $this->haveConstitutiveAct;
    }

    public function setHaveConstitutiveAct($haveConstitutiveAct): static
    {
        $this->haveConstitutiveAct = (bool) $haveConstitutiveAct;

        return $this;
    }

    public function isHaveInternalRegulations(): ?bool
    {
        return $this->haveInternalRegulations;
    }

    public function setHaveInternalRegulations($haveInternalRegulations): static
    {
        $this->haveInternalRegulations = (bool) $haveInternalRegulations;

        return $this;
    }

    public function isHaveAdministrationProceduresManual(): ?bool
    {
        return $this->haveAdministrationProceduresManual;
    }

    public function setHaveAdministrationProceduresManual($haveAdministrationProceduresManual): static
    {
        $this->haveAdministrationProceduresManual = (bool) $haveAdministrationProceduresManual;

        return $this;
    }

    public function isHaveFinanceProceduresManual(): ?bool
    {
        return $this->haveFinanceProceduresManual;
    }

    public function setHaveFinanceProceduresManual($haveFinanceProceduresManual): static
    {
        $this->haveFinanceProceduresManual = (bool) $haveFinanceProceduresManual;

        return $this;
    }

    public function isHaveManagementConsultancy(): ?bool
    {
        return $this->haveManagementConsultancy;
    }

    public function setHaveManagementConsultancy($haveManagementConsultancy): static
    {
        $this->haveManagementConsultancy = (bool) $haveManagementConsultancy;

        return $this;
    }

    public function isHaveAccounting(): ?bool
    {
        return $this->haveAccounting;
    }

    public function setHaveAccounting($haveAccounting): static
    {
        $this->haveAccounting = (bool) $haveAccounting;

        return $this;
    }

    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    public function setDocumentType(string $documentType): static
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getDocumentPhoto()
    {
        return $this->documentPhoto;
    }

    public function setDocumentPhoto($documentPhoto): static
    {
        if (!($documentPhoto instanceof UploadedFile)) {
            new \Exception("Not support yet");
        }
        $this->documentPhoto = $documentPhoto;

        return $this;
    }

    public function getCountVolunteerStaff(): ?int
    {
        return $this->countVolunteerStaff;
    }

    public function setCountVolunteerStaff($countVolunteerStaff): static
    {
        $this->countVolunteerStaff = (int) $countVolunteerStaff;

        return $this;
    }

    public function getCountStaffPaid(): ?int
    {
        return $this->countStaffPaid;
    }

    public function setCountStaffPaid($countStaffPaid): static
    {
        $this->countStaffPaid = (int) $countStaffPaid;

        return $this;
    }

    public function getYearFirstTaxPayment(): ?int
    {
        return $this->yearFirstTaxPayment;
    }

    public function setYearFirstTaxPayment($yearFirstTaxPayment): static
    {
        $this->yearFirstTaxPayment = (int) $yearFirstTaxPayment;

        return $this;
    }

    public function getTaxeNames(): string
    {
        return $this->taxeNames;
    }

    public function setTaxeNames(string $taxeNames): static
    {
        $this->taxeNames = $taxeNames;

        return $this;
    }

    public function getAmountPaidMonth(): ?float
    {
        return $this->amountPaidMonth;
    }

    public function setAmountPaidMonth($amountPaidMonth): static
    {
        $this->amountPaidMonth = (float) $amountPaidMonth;

        return $this;
    }

    public function getAmountPaidQuarter(): ?float
    {
        return $this->amountPaidQuarter;
    }

    public function setAmountPaidQuarter($amountPaidQuarter): static
    {
        $this->amountPaidQuarter = (float) $amountPaidQuarter;

        return $this;
    }

    public function getAmountPaidSemester(): ?float
    {
        return $this->amountPaidSemester;
    }

    public function setAmountPaidSemester($amountPaidSemester): static
    {
        $this->amountPaidSemester = (float) $amountPaidSemester;

        return $this;
    }

    public function getAmountPaidAnnually(): ?float
    {
        return $this->amountPaidAnnually;
    }

    public function setAmountPaidAnnually($amountPaidAnnually): static
    {
        $this->amountPaidAnnually = (float) $amountPaidAnnually;

        return $this;
    }

    public function isUseMobileBank(): ?bool
    {
        return $this->useMobileBank;
    }

    public function setUseMobileBank($useMobileBank): static
    {
        $this->useMobileBank = (bool) $useMobileBank;

        return $this;
    }

    public function isUseCommercialBank(): ?bool
    {
        return $this->useCommercialBank;
    }

    public function setUseCommercialBank($useCommercialBank): static
    {
        $this->useCommercialBank =  (bool) $useCommercialBank;

        return $this;
    }

    public function isUseMicrofinance(): ?bool
    {
        return $this->useMicrofinance;
    }

    public function setUseMicrofinance($useMicrofinance): static
    {
        $this->useMicrofinance =  (bool) $useMicrofinance;

        return $this;
    }

    public function getAddressLine(): ?string
    {
        return $this->addressLine;
    }

    public function setAddressLine(string $addressLine): static
    {
        $this->addressLine = $addressLine;

        return $this;
    }

    public function getTown(): ?Town
    {
        return $this->town;
    }

    public function setTown(?Town $town): static
    {
        $this->town = $town;

        return $this;
    }

    public function getTerritory(): ?Territorry
    {
        return $this->territory;
    }

    public function setTerritory(?Territorry $territory): static
    {
        $this->territory = $territory;

        return $this;
    }

    public function getTurnover(): ?TurnoverRange
    {
        return $this->turnover;
    }

    public function setTurnover(?TurnoverRange $turnover): static
    {
        //dd($turnover);
        $this->turnover = $turnover;

        return $this;
    }

    public function getLegalStatus(): ?LegalStatus
    {
        return $this->legalStatus;
    }

    public function setLegalStatus(?LegalStatus $legalStatus): static
    {
        $this->legalStatus = $legalStatus;

        return $this;
    }

    public function getProductDisplayMode(): ?ProductDisplayMode
    {
        return $this->productDisplayMode;
    }

    public function setProductDisplayMode(?ProductDisplayMode $productDisplayMode): static
    {
        $this->productDisplayMode = $productDisplayMode;

        return $this;
    }

    public function getAmountPaidDay() : ?float
    {
        return $this->amountPaidDay;
    }

    public function setAmountPaidDay($amountPaidDay): static
    {
        //dd($amountPaidDay);
        $this->amountPaidDay = (float) $amountPaidDay;

        return $this;
    }

    public function getProductor(): ?Productor
    {
        return $this->productor;
    }

    public function setProductor(?Productor $productor): static
    {
        $this->productor = $productor;
        $this->productor->addEntrepreneurialActivity($this);

        return $this;
    }

    public function getDocumentPath(): ?string
    {
        return $this->documentPath;
    }

    public function setDocumentPath(string $documentPath): static
    {
        $this->documentPath = $documentPath;

        return $this;
    }

    public function getTaxeAmount(): ?float
    {
        return $this->taxeAmount;
    }

    public function setTaxeAmount($taxeAmount): static
    {
        $this->taxeAmount = (float) $taxeAmount;

        return $this;
    }

    public function getTaxePayMode(): ?TaxePayMode
    {
        return $this->taxePayMode;
    }

    public function setTaxePayMode(?TaxePayMode $taxePayMode): static
    {
        $this->taxePayMode = $taxePayMode;

        return $this;
    }

    public function getTaxes(): ?array
    {
        return $this->taxes;
    }

    public function setTaxes(?array $taxes): static
    {
        $this->taxes = $taxes;

        return $this;
    }

    public function getActivities(): ?array
    {
        return $this->activities;
    }

    public function setActivities(?array $activities): static
    {
        $this->activities = $activities;

        return $this;
    }

    public function getYearOfLegalization(): ?float
    {
        return $this->yearOfLegalization;
    }

    public function setYearOfLegalization($yearOfLegalization): static
    {
        $this->yearOfLegalization = (int) $yearOfLegalization;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setActivity($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getActivity() === $this) {
                $document->setActivity(null);
            }
        }

        return $this;
    }
}
