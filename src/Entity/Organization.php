<?php

namespace App\Entity;

#use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
/*#[ApiResource(
    normalizationContext:["groups" => ["read:organization"]]
)]*/
class Organization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Groups(["read:organization", "read:productor:personnal_id_data"])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'organizations')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Groups(["read:organization", "read:productor:personnal_id_data"])]
    private ?City $city = null;

    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: Productor::class)]
    private Collection $productors;

    #[ORM\Column(length: 255)]
    private ?string $myHash = null;

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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

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
            $productor->setOrganization($this);
        }

        return $this;
    }

    public function removeProductor(Productor $productor): static
    {
        if ($this->productors->removeElement($productor)) {
            // set the owning side to null (unless already changed)
            if ($productor->getOrganization() === $this) {
                $productor->setOrganization(null);
            }
        }

        return $this;
    }

    public function getMyHash(): ?string
    {
        return $this->myHash;
    }

    public function setMyHash(string $myHash): static
    {
        $this->myHash = $myHash;

        return $this;
    }
}
