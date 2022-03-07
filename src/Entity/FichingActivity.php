<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use App\Repository\FichingActivityRepository;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Utils\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=FichingActivityRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *      normalizationContext={"groups": {"read:fichingacollection","timestamp:read","slug:read"}},
 *      denormalizationContext={"groups":{"write:FichingActivity"}},
 * 
 *      collectionOperations={
 *         "fiching-activities-vue"={
 *             "method"="GET",
 *             "path"="/fiching-activities",
 *             "openapi_context"={
 *                  "summary"= "Voir les activités pêches"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "path"="/fiching-activities",
 *             "denormalization_context"={"groups":{"write:FichingActivity"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter une activité pêche"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get"={
 *            "method"="GET",
 *             "path"="/fiching-activities/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier une activité pêche"
 *              }
 *          } ,
 *         "fiching-activities-update"={
 *            "denormalization_context"={"groups":{"write:FichingActivity"}},
 *            "method"="PATCH",
 *             "path"="/fiching-activities/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier une activité pêche"
 *              }
 *          } 
 *       } 
 * )
 */
class FichingActivity
{

    use TimestampTrait;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:activities_data","read:fichingacollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read:productor:activities_data","read:fichingacollection","write:FichingActivity"})
     * @Assert\NotNull
     * 
     */
    private $activityCreateDate;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:productor:activities_data","write:FichingActivity","read:fichingacollection"})
     * @Assert\NotNull
     * @Assert\Type("string")
     */
    private $goal;

    /**
     * @ORM\ManyToOne(targetEntity=Productor::class, inversedBy="fichingactivity")
     */
    private $productor;

    /**
     * @Groups({"read:productor:activities_data","write:FichingActivity","read:fichingacollection"})
     * @ORM\ManyToOne(targetEntity=SourceOfSupplyActivity::class, inversedBy="fichingActivities")
     * @Assert\NotNull
     * 
     */
    private $sourceOfSupplyActivity;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     */
    private $address;

    /**
     * @Groups({"read:productor:activities_data","write:FichingActivity"})
     * @ORM\ManyToOne(targetEntity=FichingActivityType::class, inversedBy="fichingActivities")
     * @Assert\NotNull
     * 
     */
    private $fichingActivityType;

    /*
    * @Groups({"read:fichingacollection"})
    */
    public function getIri(): string
    {
        return '/productors/fiching-activities'. $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivityCreateDate(): ?\DateTimeInterface
    {
        return $this->activityCreateDate;
    }

    public function setActivityCreateDate(\DateTimeInterface $createdate): self
    {
        $this->activityCreateDate = $createdate;

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
