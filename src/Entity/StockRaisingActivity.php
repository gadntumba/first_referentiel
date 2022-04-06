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

/**
 * @ORM\Entity(repositoryClass=StockRaisingActivityRepository::class)
 * @ORM\HasLifecycleCallbacks()
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
 *                  "summary"= "Modifier une activité d'elevage"
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
 * @UniqueEntity(
 *     fields= "goal",
 *     errorPath="goal",
 *     message="Ce goal existe déjà"
 * )
 */
class StockRaisingActivity
{

    use TimestampTrait;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:activities_data","read:stockrasingcollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read:productor:activities_data","read:stockraisingcollection","white:stock-raising-activity"})
     * @Assert\NotNull
     */
    private $activityCreateDate;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:productor:activities_data","read:stockraisingcollection","white:stock-raising-activity"})
     * @Assert\NotNull
     * @Assert\Type("string")
     * @Assert\Length(
     *  min = 3,
     *  max = 50,
     *  groups={"postValidation"}  
     *)
     */
    private $goal;

    /**
     * @ORM\ManyToOne(targetEntity=Productor::class, inversedBy="raisingactivity")
     */
    private $productor;

    /**
     * @Groups({"read:productor:activities_data","read:stockraisingcollection","white:stock-raising-activity"})
     * @ORM\ManyToOne(targetEntity=StockRainsingActivityType::class, inversedBy="stockRaisingActivities")
     * @Assert\NotNull
     */
    private $stockRainsingActivityType;

    /**
     * @Groups({"read:productor:activities_data","read:stockraisingcollection","white:stock-raising-activity"})
     * @ORM\ManyToOne(targetEntity=SourceOfSupplyActivity::class, inversedBy="stockRaisingActivities")
     * @Assert\NotNull
     */
    private $sourceOfSupplyActivity;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public static function validationGroups(self $stockRaisingActivity){
        return ['create:StockRaisingActivity'];
    }
    public function getActivityCreateDate(): ?\DateTimeInterface
    {
        return $this->activityCreateDate;
    }

    public function setActivityCreateDate(\DateTimeInterface $createDate): self
    {
        $this->activityCreateDate = $createDate;

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
}
