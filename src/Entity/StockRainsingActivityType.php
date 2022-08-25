<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use App\Repository\StockRainsingActivityTypeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:StockRainsingActivityTypeRepository::class)]
#[ORM\HasLifecycleCallbacks()]
/**
 * @ApiResource(
 *      normalizationContext={"groups": {"read:stocktypecollection","timestamp:read","slug:read"}},
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
 * @UniqueEntity(
 *     fields= "libelle",
 *     errorPath="libelle",
 *     message="Ce libelle existe déjà"
 * )
 */
class StockRainsingActivityType
{

    use TimestampTrait;
    
    /**
     * 
     * 
     * 
     * @Groups({"read:stockRaisingGroup","read:productor:activities_data","read:stocktypecollection","read:stockRaisingGroup"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    /**
     * 
     * @Groups({"read:stockRaisingGroup","read:productor:activities_data","read:stockRaisingGroup","read:stocktypecollection","read:StockRainsingActivityType"})
     * @Assert\NotBlank
     * @Assert\Length(
     *  min = 3,
     *  max = 50,
     *  groups={"postValidation"}  
     *)
     */
    #[ORM\Column(type:"string", length:255)]
    private $libelle;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:StockRaisingActivity::class, mappedBy:"stockRainsingActivityType")]
    private $stockRaisingActivities;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:StockRaisingActivitySubType::class, mappedBy:"stockRainsingActivityType")]
    private $stockRaisingActivitySubType;

    #[ORM\ManyToOne(inversedBy: 'stockRainsingActivityTypes')]
    #[Groups(["read:productor:activities_data","read:stockRaisingGroup","read:stocktypecollection","read:StockRainsingActivityType"])]
    private ?StockRaisingActivityTypeGroup $stockRaisingActivityTypeGroup = null;

    public function __construct()
    {
        $this->stockRaisingActivities = new ArrayCollection();
        $this->stockRaisingActivitySubType = new ArrayCollection();
    }

    public static function validationGroups(self $stockRainsingActivityType){
        return ['create:StockRainsingActivityType'];
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

    /**
     * @return Collection<int, StockRaisingActivitySubType>
     */
    public function getStockRaisingActivitySubType(): Collection
    {
        return $this->stockRaisingActivitySubType;
    }

    public function addStockRaisingActivitySubType(StockRaisingActivitySubType $stockRaisingActivitySubType): self
    {
        if (!$this->stockRaisingActivitySubType->contains($stockRaisingActivitySubType)) {
            $this->stockRaisingActivitySubType[] = $stockRaisingActivitySubType;
            $stockRaisingActivitySubType->setStockRainsingActivityType($this);
        }

        return $this;
    }

    public function removeStockRaisingActivitySubType(StockRaisingActivitySubType $stockRaisingActivitySubType): self
    {
        if ($this->stockRaisingActivitySubType->removeElement($stockRaisingActivitySubType)) {
            // set the owning side to null (unless already changed)
            if ($stockRaisingActivitySubType->getStockRainsingActivityType() === $this) {
                $stockRaisingActivitySubType->setStockRainsingActivityType(null);
            }
        }

        return $this;
    }

    public function getStockRaisingActivityTypeGroup(): ?StockRaisingActivityTypeGroup
    {
        return $this->stockRaisingActivityTypeGroup;
    }

    public function setStockRaisingActivityTypeGroup(?StockRaisingActivityTypeGroup $stockRaisingActivityTypeGroup): self
    {
        $this->stockRaisingActivityTypeGroup = $stockRaisingActivityTypeGroup;

        return $this;
    }
}
