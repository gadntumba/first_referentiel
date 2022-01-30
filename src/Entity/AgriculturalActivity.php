<?php

namespace App\Entity;

use App\Repository\AgriculturalActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgriculturalActivityRepository::class)
 */
class AgriculturalActivity
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
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $goal;

    /**
     * @ORM\ManyToOne(targetEntity=Productor::class, inversedBy="AgriculturalActivity")
     */
    private $productor;

    /**
     * @ORM\ManyToOne(targetEntity=ExploitedArea::class, inversedBy="agriculturalActivities")
     */
    private $exploitedArea;

    /**
     * @ORM\OneToOne(targetEntity=SourceOfSupplyActivity::class, cascade={"persist", "remove"})
     */
    private $sourceOfSupplyActivity;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="agriculturalActivities")
     */
    private $adress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getExploitedArea(): ?ExploitedArea
    {
        return $this->exploitedArea;
    }

    public function setExploitedArea(?ExploitedArea $exploitedArea): self
    {
        $this->exploitedArea = $exploitedArea;

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

    public function getAdress(): ?Address
    {
        return $this->adress;
    }

    public function setAdress(?Address $adress): self
    {
        $this->adress = $adress;

        return $this;
    }
}
