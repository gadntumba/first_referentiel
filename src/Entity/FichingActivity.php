<?php

namespace App\Entity;

use App\Repository\FichingActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FichingActivityRepository::class)
 */
class FichingActivity
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
    private $createdate;

    /**
     * @ORM\Column(type="text")
     */
    private $goal;

    /**
     * @ORM\ManyToOne(targetEntity=Productor::class, inversedBy="fichingactivity")
     */
    private $productor;

    /**
     * @ORM\ManyToOne(targetEntity=SourceOfSupplyActivity::class, inversedBy="fichingActivities")
     */
    private $sourceOfSupplyActivity;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=FichingActivityType::class, inversedBy="fichingActivities")
     */
    private $fichingActivityType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedate(): ?\DateTimeInterface
    {
        return $this->createdate;
    }

    public function setCreatedate(\DateTimeInterface $createdate): self
    {
        $this->createdate = $createdate;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getProductor(): ?Productor
    {
        return $this->productor;
    }

    public function setProductor(?Productor $productor): self
    {
        $this->productor = $productor;

        return $this;
    }

    public function getSourceOfSupplyActivity(): ?SourceOfSupplyActivity
    {
        return $this->sourceOfSupplyActivity;
    }

    public function setSourceOfSupplyActivity(?SourceOfSupplyActivity $sourceOfSupplyActivity): self
    {
        $this->sourceOfSupplyActivity = $sourceOfSupplyActivity;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getFichingActivityType(): ?FichingActivityType
    {
        return $this->fichingActivityType;
    }

    public function setFichingActivityType(?FichingActivityType $fichingActivityType): self
    {
        $this->fichingActivityType = $fichingActivityType;

        return $this;
    }
}
