<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MetaDataBrutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MetaDataBrutRepository::class)]
#[UniqueEntity(fields:["cityName","fileName","source","cheetTitle"])]
#[ApiResource]
class MetaDataBrut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $cityName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $fileName = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private array $thisSchema = [];

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    private ?string $source = null;

    #[ORM\OneToMany(mappedBy: 'mataData', targetEntity: DataBrut::class)]
    private Collection $dataBruts;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull()]
    private ?array $otherContent = null;

    #[ORM\Column(nullable: true)]
    private ?array $otherContent2 = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private ?string $cheetTitle = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isCharged = null;

    public function __construct()
    {
        $this->dataBruts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(string $cityName): static
    {
        $this->cityName = $cityName;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getThisSchema(): array
    {
        return $this->thisSchema;
    }

    public function setThisSchema(array $thisSchema): static
    {
        $this->thisSchema = $thisSchema;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return Collection<int, DataBrut>
     */
    public function getDataBruts(): Collection
    {
        return $this->dataBruts;
    }

    public function addDataBrut(DataBrut $dataBrut): static
    {
        if (!$this->dataBruts->contains($dataBrut)) {
            $this->dataBruts->add($dataBrut);
            $dataBrut->setMataData($this);
        }

        return $this;
    }

    public function removeDataBrut(DataBrut $dataBrut): static
    {
        if ($this->dataBruts->removeElement($dataBrut)) {
            // set the owning side to null (unless already changed)
            if ($dataBrut->getMataData() === $this) {
                $dataBrut->setMataData(null);
            }
        }

        return $this;
    }

    public function getOtherContent(): ?array
    {
        return $this->otherContent;
    }

    public function setOtherContent(?array $otherContent): static
    {
        $this->otherContent = $otherContent;

        return $this;
    }

    public function getOtherContent2(): ?array
    {
        return $this->otherContent2;
    }

    public function setOtherContent2(?array $otherContent2): static
    {
        $this->otherContent2 = $otherContent2;

        return $this;
    }

    public function getCheetTitle(): ?string
    {
        return $this->cheetTitle;
    }

    public function setCheetTitle(string $cheetTitle): static
    {
        $this->cheetTitle = $cheetTitle;

        return $this;
    }

    public function isIsCharged(): ?bool
    {
        return $this->isCharged;
    }

    public function setIsCharged(?bool $isCharged): static
    {
        $this->isCharged = $isCharged;

        return $this;
    }
}
