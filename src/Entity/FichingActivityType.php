<?php

namespace App\Entity;

use App\Repository\FichingActivityTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FichingActivityTypeRepository::class)
 * @ApiResource(
 *      denormalizationContext={"groups":{"write:FichingActivity"}},
 *      normalizationContext={"groups": {"read:fichingcollection"}},
 *      collectionOperations={
 *         "fiching-activities-types-vue"={
 *             "method"="GET",
 *             "path"="/fiching-activities/p/types",
 *             "openapi_context"={
 *                  "summary"= "Voir les types de pêche"
 *              }
 *          },
 *         "fiching-activities-types-add"={
 *             "method"="POST",
 *             "path"="/fiching-activities/p/types",
 *             "denormalization_context"={"groups":{"read:FichingActivityType"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un type de pêche"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get"={
 *            "method"="GET",
 *             "path"="/fiching-activities/p/types/{id}",
 *             "openapi_context"={
 *                  "summary"= ""
 *              }
 *          } ,
 *         "fichingactivities-types-update"={
 *            "denormalization_context"={"groups":{"read:FichingActivityType"}},
 *            "method"="PATCH",
 *             "path"="/fiching-activities/p/types/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un type de pêche"
 *              }
 *          } 
 *       }
 * 
 * )
 */
class FichingActivityType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:activities_data","read:fichingcollection", "write:FichingActivity"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:activities_data","read:fichingcollection","read:FichingActivityType"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=FichingActivity::class, mappedBy="fichingActivityType")
     */
    private $fichingActivities;

    public function __construct()
    {
        $this->fichingActivities = new ArrayCollection();
    }

    /*
    * @Groups({"read:fichingcollection"})
    */
    public function getIri(): string
    {
        return '/api/fiching-activities/p/types/'. $this->id;
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
            $fichingActivity->setFichingActivityType($this);
        }

        return $this;
    }

    public function removeFichingActivity(FichingActivity $fichingActivity): self
    {
        if ($this->fichingActivities->removeElement($fichingActivity)) {
            // set the owning side to null (unless already changed)
            if ($fichingActivity->getFichingActivityType() === $this) {
                $fichingActivity->setFichingActivityType(null);
            }
        }

        return $this;
    }
}
