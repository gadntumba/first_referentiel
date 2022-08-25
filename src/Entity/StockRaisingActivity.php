<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\StockRaisingActivityRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass:StockRaisingActivityRepository::class)]
#[ORM\HasLifecycleCallbacks()]
/**
 * @ApiResource(
 *      normalizationContext={"groups": {"read:stockraisingcollection","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "stock-raising-activities-vue"={
 *             "method"="GET",
 *             "path"="/stock-raising-activities",
 *             "openapi_context"={
 *                  "summary"= "Voir les activités d'elevage"
 *              }
 *          },
 *         "stock-raising-activities-add"={
 *             "method"="POST",
 *             "path"="/stock-raising-activities",
 *             "denormalization_context"={"groups":{"white:stock-raising-activity"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter une activité d'elevage"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get"={
 *            "method"="GET",
 *             "path"="/stock-raising-activities/{id}",
 *             "openapi_context"={
 *                  "summary"= "Voir une activité d'elevage"
 *              }
 *          } ,
 *         "stock-raising-activities-update"={
 *            "denormalization_context"={"groups":{"white:stock-raising-activity"}},
 *            "method"="PATCH",
 *             "path"="/stock-raising-activities/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier une activité d'elevage"
 *              }
 *          } 
 *       }
 * )
 */
class StockRaisingActivity
{

    use TimestampTrait;
    
    /**
     * @Groups({"read:productor:activities_data","read:stockrasingcollection"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    /**
     * 
     * @Groups({"read:productor:activities_data","read:stockraisingcollection","white:stock-raising-activity"})
     * 
     */
    #[ORM\Column(type:"integer")]
    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual(value: 1885)]
    private $activityCreateYear = null;

    /**
     * 
     * @Groups({"read:productor:activities_data","read:stockraisingcollection","white:stock-raising-activity"})
     * 
     * @Assert\Type("string")
     * @Assert\Length(
     *  min = 3,
     *  max = 50,
     *  groups={"postValidation"}  
     *)
     */
    #[ORM\Column(type:"text")]
    private $goal;

    /**
     * 
     */
    #[ORM\ManyToOne(targetEntity:Productor::class, inversedBy:"raisingactivity")]
    private $productor;

    /**
     * @Groups({"read:productor:activities_data","read:stockraisingcollection","white:stock-raising-activity"})
     * 
     * @Assert\NotNull
     */
    #[ORM\ManyToOne(targetEntity:StockRainsingActivityType::class, inversedBy:"stockRaisingActivities")]
    private $stockRainsingActivityType;

    /**
     * @Groups({"read:productor:activities_data","read:stockraisingcollection","white:stock-raising-activity"})
     * 
     * @Assert\NotNull
     */
    #[ORM\ManyToOne(targetEntity:SourceOfSupplyActivity::class, inversedBy:"stockRaisingActivities")]
    private $sourceOfSupplyActivity;

    /**
     * 
     * @Groups({"read:productor:activities_data","read:stockraisingcollection","white:stock-raising-activity"})
     */
    #[ORM\ManyToOne(targetEntity:StockRaisingActivitySubType::class, inversedBy:"stockRaisingActivities")]
    private $stockRaisingActivitySubType;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public static function validationGroups(self $stockRaisingActivity){
        return ['create:StockRaisingActivity'];
    }
    public function getActivityCreateYear()
    {
        return $this->activityCreateYear;
    }

    public function setActivityCreateYear($createYear): self
    {
        $this->activityCreateYear = $createYear;

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

    public function getStockRainsingActivityType(): ?StockRainsingActivityType
    {
        return $this->stockRainsingActivityType;
    }

    public function setStockRainsingActivityType(?StockRainsingActivityType $stockRainsingActivityType): self
    {
        $this->stockRainsingActivityType = $stockRainsingActivityType;

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

    public function getStockRaisingActivitySubType(): ?StockRaisingActivitySubType
    {
        return $this->stockRaisingActivitySubType;
    }

    public function setStockRaisingActivitySubType(?StockRaisingActivitySubType $stockRaisingActivitySubType): self
    {
        $this->stockRaisingActivitySubType = $stockRaisingActivitySubType;

        return $this;
    }
}
