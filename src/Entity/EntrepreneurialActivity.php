<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\EntrepreneurialActivity\LegalStatus;
use App\Entity\EntrepreneurialActivity\ProductDisplayMode;
use App\Entity\EntrepreneurialActivity\TurnoverRange;
use App\Repository\EntrepreneurialActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
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

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private ?string $documentPhoto = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Type("int")]
    #[Assert\PositiveOrZero()]
    private ?string $countVolunteerStaff = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Type("int")]
    #[Assert\PositiveOrZero()]
    private ?string $countStaffPaid = null;

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Type("int")]
    #[Assert\Positive()]
    #[Assert\GreaterThan(1700)]
    private ?int $yearFirstTaxPayment = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private array $taxeNames = [];

    #[ORM\Column(nullable:true)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Type("float")]
    #[Assert\PositiveOrZero()]
    private ?float $amountPaidMonth = null;

    #[ORM\Column(nullable:true)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("float")]
    #[Assert\PositiveOrZero()]
    private ?float $amountPaidQuarter = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("float")]
    #[Assert\PositiveOrZero()]
    private ?float $amountPaidSemester = null;

    #[ORM\Column(nullable:true)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("float")]
    #[Assert\PositiveOrZero()]
    private ?float $amountPaidAnnually = null;

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

    #[ORM\Column(length: 255)]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
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

    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    #[Assert\Type("float")]
    #[Assert\PositiveOrZero()]
    private ?float $amountPaidDay = null;

    #[ORM\ManyToOne(inversedBy: 'entrepreneurialActivities')]
    private ?Productor $productor = null;

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

    public function getDocumentPhoto(): ?string
    {
        return $this->documentPhoto;
    }

    public function setDocumentPhoto(string $documentPhoto): static
    {
        $this->documentPhoto = $documentPhoto;

        return $this;
    }

    public function getCountVolunteerStaff(): ?string
    {
        return $this->countVolunteerStaff;
    }

    public function setCountVolunteerStaff(string $countVolunteerStaff): static
    {
        $this->countVolunteerStaff = $countVolunteerStaff;

        return $this;
    }

    public function getCountStaffPaid(): ?string
    {
        return $this->countStaffPaid;
    }

    public function setCountStaffPaid(string $countStaffPaid): static
    {
        $this->countStaffPaid = $countStaffPaid;

        return $this;
    }

    public function getYearFirstTaxPayment(): ?int
    {
        return $this->yearFirstTaxPayment;
    }

    public function setYearFirstTaxPayment(int $yearFirstTaxPayment): static
    {
        $this->yearFirstTaxPayment = $yearFirstTaxPayment;

        return $this;
    }

    public function getTaxeNames(): array
    {
        return $this->taxeNames;
    }

    public function setTaxeNames(array $taxeNames): static
    {
        $this->taxeNames = $taxeNames;

        return $this;
    }

    public function getAmountPaidMonth(): ?float
    {
        return $this->amountPaidMonth;
    }

    public function setAmountPaidMonth(float $amountPaidMonth): static
    {
        $this->amountPaidMonth = $amountPaidMonth;

        return $this;
    }

    public function getAmountPaidQuarter(): ?float
    {
        return $this->amountPaidQuarter;
    }

    public function setAmountPaidQuarter(float $amountPaidQuarter): static
    {
        $this->amountPaidQuarter = $amountPaidQuarter;

        return $this;
    }

    public function getAmountPaidSemester(): ?float
    {
        return $this->amountPaidSemester;
    }

    public function setAmountPaidSemester(?float $amountPaidSemester): static
    {
        $this->amountPaidSemester = $amountPaidSemester;

        return $this;
    }

    public function getAmountPaidAnnually(): ?float
    {
        return $this->amountPaidAnnually;
    }

    public function setAmountPaidAnnually(float $amountPaidAnnually): static
    {
        $this->amountPaidAnnually = $amountPaidAnnually;

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

    public function getAmountPaidDay(): ?float
    {
        return $this->amountPaidDay;
    }

    public function setAmountPaidDay(float $amountPaidDay): static
    {
        $this->amountPaidDay = $amountPaidDay;

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
    }
}
