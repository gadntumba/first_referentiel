<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StockRaisingActivitySubTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRaisingActivitySubTypeRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *      normalizationContext={"groups": {"read:stockraisingsubcollection","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "stock-raising-activities-vue"={
 *             "method"="GET",
 *             "path"="/stock_raising_activities_subtypes",
 *             "openapi_context"={
 *                  "summary"= "Voir les sous types d'activités d'elevages"
 *              }
 *          },
 *         "stock-raising-activities-add"={
 *             "method"="POST",
 *             "path"="/stock_raising_activities_subtypes",
 *             "denormalization_context"={"groups":{"write:StockRaisingActivitySubType"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un sous type d'activités d'elevages"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get"={
 *            "method"="GET",
 *             "path"="/stock_raising_activities_subtypes/{id}",
 *             "openapi_context"={
 *                  "summary"= "Voir un sous type d'activité d'elevage"
 *              }
 *          } ,
 *         "stock-raising-activities-update"={
 *            "denormalization_context"={"groups":{"write:StockRaisingActivitySubType"}},
 *            "method"="PATCH",
 *             "path"="/stock_raising_activities_subtypes/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un sous type d'activité d'elevage"
 *              }
 *          } 
 *       }
 * )
 */
class StockRaisingActivitySubType
{
    use TimestampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:activities_data","read:stockraisingsubcollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:activities_data","write:StockRaisingActivitySubType","read:stockraisingsubcollection"})
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
