<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InstigatorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstigatorRepository::class)]
#[ApiResource]
class Instigator
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    private array $location = [];

    #[ORM\OneToMany(mappedBy: 'instigator', targetEntity: Productor::class)]
    private Collection $productors;

    public function __construct()
    {
        $this->productors = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getLocation(): array
    {
        return $this->location;
    }

    public function setLocation(array $location): static
    {
        $this->location = $location;

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
            $productor->setInstigator($this);
        }

        return $this;
    }

    public function removeProductor(Productor $productor): static
    {
        if ($this->productors->removeElement($productor)) {
            // set the owning side to null (unless already changed)
            if ($productor->getInstigator() === $this) {
                $productor->setInstigator(null);
            }
        }

        return $this;
    }
}
