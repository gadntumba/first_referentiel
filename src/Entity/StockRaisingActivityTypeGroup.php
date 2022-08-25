<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StockRaisingActivityTypeGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utils\TimestampTrait;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StockRaisingActivityTypeGroupRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ApiResource(
    normalizationContext={"groups": {"read:stockRaisingGroup","timestamp:read","slug:read"}})
]
class StockRaisingActivityTypeGroup
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:stockRaisingGroup"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Groups(["read:stockRaisingGroup"])]
    private ?string $wording;

    #[ORM\OneToMany(mappedBy: 'stockRaisingActivityTypeGroup', targetEntity: StockRainsingActivityType::class)]
    #[Groups(["read:stockRaisingGroup"])]
    private Collection $stockRainsingActivityTypes;

    public function __construct()
    {
        $this->stockRainsingActivityTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): self
    {
        $this->wording = $wording;

        return $this;
    }

    /**
     * @return Collection<int, StockRainsingActivityType>
     */
    public function getStockRainsingActivityTypes(): Collection
    {
        return $this->stockRainsingActivityTypes;
    }

    public function addStockRainsingActivityType(StockRainsingActivityType $stockRainsingActivityType): self
    {
        if (!$this->stockRainsingActivityTypes->contains($stockRainsingActivityType)) {
            $this->stockRainsingActivityTypes->add($stockRainsingActivityType);
            $stockRainsingActivityType->setStockRaisingActivityTypeGroup($this);
        }

        return $this;
    }

    public function removeStockRainsingActivityType(StockRainsingActivityType $stockRainsingActivityType): self
    {
        if ($this->stockRainsingActivityTypes->removeElement($stockRainsingActivityType)) {
            // set the owning side to null (unless already changed)
            if ($stockRainsingActivityType->getStockRaisingActivityTypeGroup() === $this) {
                $stockRainsingActivityType->setStockRaisingActivityTypeGroup(null);
            }
        }

        return $this;
    }
}
