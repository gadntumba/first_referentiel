<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MaritalStateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaritalStateRepository::class)]
#[ApiResource]
class MaritalState
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wording = null;

    #[ORM\OneToMany(mappedBy: 'maritalState', targetEntity: Productor::class)]
    private Collection $productors;

    public function __construct()
    {
        $this->productors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(?string $wording): static
    {
        $this->wording = $wording;

        return $this;
    }

    /**
     * @return Collection<int, Productor>
     */
    public function getProductors(): Collection
    {
        return $this->productors;
    }

    public function addProductor(Productor $productor): static
    {
        if (!$this->productors->contains($productor)) {
            $this->productors->add($productor);
            $productor->setMaritalState($this);
        }

        return $this;
    }

    public function removeProductor(Productor $productor): static
    {
        if ($this->productors->removeElement($productor)) {
            // set the owning side to null (unless already changed)
            if ($productor->getMaritalState() === $this) {
                $productor->setMaritalState(null);
            }
        }

        return $this;
    }
}
