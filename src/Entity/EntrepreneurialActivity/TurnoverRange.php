<?php

namespace App\Entity\EntrepreneurialActivity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\EntrepreneurialActivity;
use App\Repository\EntrepreneurialActivity\TurnoverRangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TurnoverRangeRepository::class)]
#[ApiResource]
class TurnoverRange
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

    #[ORM\OneToMany(mappedBy: 'turnover', targetEntity: EntrepreneurialActivity::class)]
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
            $entrepreneurialActivity->setTurnover($this);
        }

        return $this;
    }

    public function removeEntrepreneurialActivity(EntrepreneurialActivity $entrepreneurialActivity): static
    {
        if ($this->entrepreneurialActivities->removeElement($entrepreneurialActivity)) {
            // set the owning side to null (unless already changed)
            if ($entrepreneurialActivity->getTurnover() === $this) {
                $entrepreneurialActivity->setTurnover(null);
            }
        }

        return $this;
    }
}
