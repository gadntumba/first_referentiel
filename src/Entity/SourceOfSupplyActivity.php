<?php

namespace App\Entity;

use App\Repository\SourceOfSupplyActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SourceOfSupplyActivityRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:sourcecollection"}},
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
 */
class SourceOfSupplyActivity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:sourcecollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write:SourceOfSupplyActivity","read:sourcecollection"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=FichingActivity::class, mappedBy="sourceOfSupplyActivity")
     * 
     */
    private $fichingActivities;

    public function __construct()
    {
        $this->fichingActivities = new ArrayCollection();
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
}
