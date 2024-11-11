<?php

namespace App\Entity;

use App\Repository\DownloadItemProductorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DownloadItemProductorRepository::class)]
class DownloadItemProductor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $dataBrut = [];

    #[ORM\ManyToOne(inversedBy: 'downloadItemProductors')]
    private ?City $city = null;

    #[ORM\Column]
    private ?int $productorId = null;

    #[ORM\ManyToOne(inversedBy: 'downloadItemProductors')]
    private ?Town $town = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataBrut(): array
    {
        return $this->dataBrut;
    }

    public function setDataBrut(array $dataBrut): static
    {
        $this->dataBrut = $dataBrut;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getProductorId(): ?int
    {
        return $this->productorId;
    }

    public function setProductorId(int $productorId): static
    {
        $this->productorId = $productorId;

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
}
