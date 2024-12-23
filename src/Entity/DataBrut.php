<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DataBrutRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataBrutRepository::class)]
#[ApiResource]
class DataBrut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $content = [];

    #[ORM\Column(length: 255)]
    private ?string $source = null;

    #[ORM\ManyToOne(inversedBy: 'dataBruts')]
    private ?MetaDataBrut $mataData = null;

    #[ORM\Column]
    private ?int $rowId = null;

    #[ORM\ManyToOne(inversedBy: 'dataBruts')]
    private ?Productor $productor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): static
    {
        $this->content = $content;

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

    public function getMataData(): ?MetaDataBrut
    {
        return $this->mataData;
    }

    public function setMataData(?MetaDataBrut $mataData): static
    {
        $this->mataData = $mataData;

        return $this;
    }

    public function getRowId(): ?int
    {
        return $this->rowId;
    }

    public function setRowId(int $rowId): static
    {
        $this->rowId = $rowId;

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
