<?php

namespace App\Entity;

use App\Repository\SupervisorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SupervisorRepository::class)
 */
class Supervisor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $goalRecordings;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="supervisors")
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=Territorry::class, inversedBy="supervisors")
     */
    private $territory;

    /**
     * @ORM\ManyToMany(targetEntity=OT::class, inversedBy="supervisors")
     */
    private $ot;

    public function __construct()
    {
        $this->ot = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getGoalRecordings(): ?int
    {
        return $this->goalRecordings;
    }

    public function setGoalRecordings(int $goalRecordings): self
    {
        $this->goalRecordings = $goalRecordings;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTerritory(): ?Territorry
    {
        return $this->territory;
    }

    public function setTerritory(?Territorry $territory): self
    {
        $this->territory = $territory;

        return $this;
    }

    /**
     * @return Collection<int, OT>
     */
    public function getOt(): Collection
    {
        return $this->ot;
    }

    public function addOt(OT $ot): self
    {
        if (!$this->ot->contains($ot)) {
            $this->ot[] = $ot;
        }

        return $this;
    }

    public function removeOt(OT $ot): self
    {
        $this->ot->removeElement($ot);

        return $this;
    }
}
