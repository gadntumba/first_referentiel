<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AgriculturalActivityTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgriculturalActivityTypeRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *      normalizationContext={"groups": {"read:agricultypecollection","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "agriculural-activities-vue"={
 *             "method"="GET",
 *             "path"="/agricultural_activities_types",
 *             "openapi_context"={
 *                  "summary"= "Voir les types d'activités agricoles"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "path"="/agricultural_activities_types",
 *             "denormalization_context"={"groups":{"write:AgriculturalActivityType"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un type d'activité agricole"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "agricultural-activities-update"={
 *            "denormalization_context"={"groups":{"write:AgriculturalActivityType"}},
 *            "method"="PATCH",
 *             "path"="/agricultural_activities_types/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un type d'activité agricole"
 *              }
 *          } 
 *       } 
 * )
 */
class AgriculturalActivityType
{

    use TimestampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:activities_data","read:agricultypecollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:activities_data","write:AgriculturalActivityType","read:agricultypecollection"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=AgriculturalActivity::class, mappedBy="agriculturalActivityType")
     */
    private $agriculturalActivities;

    public function __construct()
    {
        $this->agriculturalActivities = new ArrayCollection();
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
     * @return Collection<int, AgriculturalActivity>
     */
    public function getAgriculturalActivities(): Collection
    {
        return $this->agriculturalActivities;
    }

    public function addAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if (!$this->agriculturalActivities->contains($agriculturalActivity)) {
            $this->agriculturalActivities[] = $agriculturalActivity;
            $agriculturalActivity->setAgriculturalActivityType($this);
        }

        return $this;
    }

    public function removeAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if ($this->agriculturalActivities->removeElement($agriculturalActivity)) {
            // set the owning side to null (unless already changed)
            if ($agriculturalActivity->getAgriculturalActivityType() === $this) {
                $agriculturalActivity->setAgriculturalActivityType(null);
            }
        }

        return $this;
    }
}
