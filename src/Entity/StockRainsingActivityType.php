<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\StockRainsingActivityTypeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRainsingActivityTypeRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:stocktypecollection"}},
 *      collectionOperations={
 *         "stock-rainsing-activities-types-vue"={
 *             "method"="GET",
 *             "path"="/stock-rainsing-activities/types",
 *             "openapi_context"={
 *                  "summary"= "Voir les types d'elevage"
 *              }
 *          },
 *         "stock-rainsing-activities-types-add"={
 *             "method"="POST",
 *             "path"="/stock-rainsing-activities/types",
 *             "denormalization_context"={"groups":{"read:StockRainsingActivityType"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un type d'elevage"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "stock-rainsing-activities-types-update"={
 *            "denormalization_context"={"groups":{"read:StockRainsingActivityType"}},
 *            "method"="PATCH",
 *             "path"="/stock-rainsing-activities/types/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un type d'elevage"
 *              }
 *          } 
 *       }
 * )
 */
class StockRainsingActivityType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:activities_data","read:stocktypecollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:activities_data","read:stocktypecollection","read:StockRainsingActivityType"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=StockRaisingActivity::class, mappedBy="stockRainsingActivityType")
     */
    private $stockRaisingActivities;

    public function __construct()
    {
        $this->stockRaisingActivities = new ArrayCollection();
    }
    /*
    * @Groups({"read:exploitedAreacollection"})
    */
    public function getIri(): string
    {
        return '/api/stock_rainsing_activity_types/'. $this->id;
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

    /**
     * @return Collection|StockRaisingActivity[]
     */
    public function getStockRaisingActivities(): Collection
    {
        return $this->stockRaisingActivities;
    }

    public function addStockRaisingActivity(StockRaisingActivity $stockRaisingActivity): self
    {
        if (!$this->stockRaisingActivities->contains($stockRaisingActivity)) {
            $this->stockRaisingActivities[] = $stockRaisingActivity;
            $stockRaisingActivity->setStockRainsingActivityType($this);
        }

        return $this;
    }

    public function removeStockRaisingActivity(StockRaisingActivity $stockRaisingActivity): self
    {
        if ($this->stockRaisingActivities->removeElement($stockRaisingActivity)) {
            // set the owning side to null (unless already changed)
            if ($stockRaisingActivity->getStockRainsingActivityType() === $this) {
                $stockRaisingActivity->setStockRainsingActivityType(null);
            }
        }

        return $this;
    }
}
