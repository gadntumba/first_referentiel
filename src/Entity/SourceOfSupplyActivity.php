<?php

namespace App\Entity;

use App\Repository\SourceOfSupplyActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:SourceOfSupplyActivityRepository::class)]
#[ORM\HasLifecycleCallbacks()]
/**
 * 
 * @ApiResource(
 *      normalizationContext={"groups": {"read:sourcecollection","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "get"={
 *             "method"="GET",
 *             "normalization_context"={"groups":{"read:sourcecollection"}},
 *             "path"="/source-supply-activities",
 *             "openapi_context"={
 *                  "summary"= "Voir les sources d'approvisionnement"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "path"="/source-supply-activities",
 *             "denormalization_context"={"groups":{"write:SourceOfSupplyActivity"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter une source d'approvisionnement"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get" = {
 *            "method"="GET",
 *             "path"="/productors/source-supply-activities/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier une source d'approvisionnement"
 *              }
 *              
 *          },
 *         "source-supply-activities-update"={
 *            "denormalization_context"={"groups":{"write:SourceOfSupplyActivity"}},
 *            "method"="PATCH",
 *             "path"="/source-supply-activities/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier une source d'approvisionnement"
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
class SourceOfSupplyActivity
{

    use TimestampTrait;
    
    /**
     * @Groups({"read:productor:activities_data","read:sourcecollection"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    /**
     * 
     * @Groups({"read:productor:activities_data","write:SourceOfSupplyActivity","read:sourcecollection"})
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
     * 
     */
    #[ORM\OneToMany(targetEntity:FichingActivity::class, mappedBy:"sourceOfSupplyActivity")]
    private $fichingActivities;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:StockRaisingActivity::class, mappedBy:"sourceOfSupplyActivity")]
    private $stockRaisingActivities;
    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:AgriculturalActivity::class, mappedBy:"sourceOfSupplyActivity")]
    private $agriculturalActivities;

    public function __construct()
    {
        $this->fichingActivities = new ArrayCollection();
        $this->stockRaisingActivities = new ArrayCollection();
    }
    public static function validationGroups(self $sourceOfSupplyActivity){
        return ['create:SourceOfSupplyActivity'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    /*
    * @Groups({"read:exploitedAreacollection"})
    */
    public function getIri(): string
    {
        return '/api/productors/source-supply-activities/'. $this->id;
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
     * @return Collection|FichingActivity[]
     */
    public function getFichingActivities(): Collection
    {
        return $this->fichingActivities;
    }

    public function addFichingActivity(FichingActivity $fichingActivity): self
    {
        if (!$this->fichingActivities->contains($fichingActivity)) {
            $this->fichingActivities[] = $fichingActivity;
            $fichingActivity->setSourceOfSupplyActivity($this);
        }

        return $this;
    }

    public function removeFichingActivity(FichingActivity $fichingActivity): self
    {
        if ($this->fichingActivities->removeElement($fichingActivity)) {
            // set the owning side to null (unless already changed)
            if ($fichingActivity->getSourceOfSupplyActivity() === $this) {
                $fichingActivity->setSourceOfSupplyActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AgriculturalActivity[]
     */
    public function getAgriculturalActivities(): Collection
    {
        return $this->agriculturalActivities;
    }

    public function addAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if (!$this->agriculturalActivities->contains($agriculturalActivity)) {
            $this->agriculturalActivities[] = $agriculturalActivity;
            $agriculturalActivity->setSourceOfSupplyActivity($this);
        }

        return $this;
    }

    public function removeAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if ($this->agriculturalActivities->removeElement($agriculturalActivity)) {
            // set the owning side to null (unless already changed)
            if ($agriculturalActivity->getSourceOfSupplyActivity() === $this) {
                $agriculturalActivity->setSourceOfSupplyActivity(null);
            }
        }

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
            $stockRaisingActivity->setSourceOfSupplyActivity($this);
        }

        return $this;
    }

    public function removeStockRaisingActivity(StockRaisingActivity $stockRaisingActivity): self
    {
        if ($this->stockRaisingActivities->removeElement($stockRaisingActivity)) {
            // set the owning side to null (unless already changed)
            if ($stockRaisingActivity->getSourceOfSupplyActivity() === $this) {
                $stockRaisingActivity->setSourceOfSupplyActivity(null);
            }
        }

        return $this;
    }

}
