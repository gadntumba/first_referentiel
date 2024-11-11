<?php

namespace App\Entity;

//use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TaxePayModeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
//#[Groups(["read:productor:activities_data","read:fichingacollection"])]
#[ORM\Entity(repositoryClass: TaxePayModeRepository::class)]
#[ApiResource]
class TaxePayMode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Groups(["read:productor:activities_data","read:fichingacollection"])]
    private ?string $wording = null;

    #[ORM\OneToMany(mappedBy: 'taxePayMode', targetEntity: EntrepreneurialActivity::class)]
    private Collection $entrepreneurialActivities;

    public function __construct()
    {
        $this->entrepreneurialActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): static
    {
        $this->wording = $wording;

        return $this;
    }

    /**
     * @return Collection<int, EntrepreneurialActivity>
     */
    public function getEntrepreneurialActivities(): Collection
    {
        return $this->entrepreneurialActivities;
    }

    public function addEntrepreneurialActivity(EntrepreneurialActivity $entrepreneurialActivity): static
    {
        if (!$this->entrepreneurialActivities->contains($entrepreneurialActivity)) {
            $this->entrepreneurialActivities->add($entrepreneurialActivity);
            $entrepreneurialActivity->setTaxePayMode($this);
        }

        return $this;
    }

    public function removeEntrepreneurialActivity(EntrepreneurialActivity $entrepreneurialActivity): static
    {
        if ($this->entrepreneurialActivities->removeElement($entrepreneurialActivity)) {
            // set the owning side to null (unless already changed)
            if ($entrepreneurialActivity->getTaxePayMode() === $this) {
                $entrepreneurialActivity->setTaxePayMode(null);
            }
        }

        return $this;
    }
}
