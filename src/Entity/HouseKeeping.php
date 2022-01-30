<?php

namespace App\Entity;

use App\Repository\HouseKeepingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HouseKeepingRepository::class)
 */
class HouseKeeping
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NIM;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\OneToMany(targetEntity=Productor::class, mappedBy="housekeeping")
     */
    private $productors;

    public function __construct()
    {
        $this->productors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNIM(): ?string
    {
        return $this->NIM;
    }

    public function setNIM(string $NIM): self
    {
        $this->NIM = $NIM;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection|Productor[]
     */
    public function getProductors(): Collection
    {
        return $this->productors;
    }

    public function addProductor(Productor $productor): self
    {
        if (!$this->productors->contains($productor)) {
            $this->productors[] = $productor;
            $productor->setHousekeeping($this);
        }

        return $this;
    }

    public function removeProductor(Productor $productor): self
    {
        if ($this->productors->removeElement($productor)) {
            // set the owning side to null (unless already changed)
            if ($productor->getHousekeeping() === $this) {
                $productor->setHousekeeping(null);
            }
        }

        return $this;
    }
}
