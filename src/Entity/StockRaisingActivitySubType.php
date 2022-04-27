<?php

namespace App\Entity;

use App\Repository\StockRaisingActivitySubTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRaisingActivitySubTypeRepository::class)
 */
class StockRaisingActivitySubType
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
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=StockRainsingActivityType::class, inversedBy="stockRaisingActivitySubType")
     */
    private $stockRainsingActivityType;

    /**
     * @ORM\OneToMany(targetEntity=StockRaisingActivity::class, mappedBy="stockRaisingActivitySubType")
     */
    private $stockRaisingActivities;

    public function __construct()
    {
        $this->stockRaisingActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getStockRainsingActivityType(): ?StockRainsingActivityType
    {
        return $this->stockRainsingActivityType;
    }

    public function setStockRainsingActivityType(?StockRainsingActivityType $stockRainsingActivityType): self
    {
        $this->stockRainsingActivityType = $stockRainsingActivityType;

        return $this;
    }

    /**
     * @return Collection<int, StockRaisingActivity>
     */
    public function getStockRaisingActivities(): Collection
    {
        return $this->stockRaisingActivities;
    }

    public function addStockRaisingActivity(StockRaisingActivity $stockRaisingActivity): self
    {
        if (!$this->stockRaisingActivities->contains($stockRaisingActivity)) {
            $this->stockRaisingActivities[] = $stockRaisingActivity;
            $stockRaisingActivity->setStockRaisingActivitySubType($this);
        }

        return $this;
    }

    public function removeStockRaisingActivity(StockRaisingActivity $stockRaisingActivity): self
    {
        if ($this->stockRaisingActivities->removeElement($stockRaisingActivity)) {
            // set the owning side to null (unless already changed)
            if ($stockRaisingActivity->getStockRaisingActivitySubType() === $this) {
                $stockRaisingActivity->setStockRaisingActivitySubType(null);
            }
        }

        return $this;
    }
}
