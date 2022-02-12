<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use App\Repository\FichingActivityRepository;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=FichingActivityRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:fichingacollection"}},
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
 *             "path"="/{id}/fiching-activities",
 *             "denormalization_context"={"groups":{"write:FichingActivity"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter une activité pêche"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "fiching-activities-update"={
 *            "denormalization_context"={"groups":{"write:FichingActivity"}},
 *            "method"="PATCH",
<<<<<<< HEAD
 *             "path"="/productors/fiching-activities/{fichingActivities}",
=======
 *             "path"="/{id}/fiching-activities/{fichingActivities}",
>>>>>>> 68d3ce543590b77c2d7b71f4ad9f00c0d1f4f343
 *             "openapi_context"={
 *                  "summary"= "Modifier une activité pêche"
 *              }
 *          } 
 *       } 
 * )
 */
class FichingActivity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:fichingacollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read:fichingacollection","write:FichingActivity"})
     */
    private $createdate;

    /**
     * @ORM\Column(type="text")
     * @Groups({"write:FichingActivity","read:fichingacollection"})
     */
    private $goal;

    /**
     * @ORM\ManyToOne(targetEntity=Productor::class, inversedBy="fichingactivity")
     */
    private $productor;

    /**
     * @ORM\ManyToOne(targetEntity=SourceOfSupplyActivity::class, inversedBy="fichingActivities")
     */
    private $sourceOfSupplyActivity;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     */
    private $address;

    /**
     * @Groups({"write:FichingActivity"})
     * @ORM\ManyToOne(targetEntity=FichingActivityType::class, inversedBy="fichingActivities", cascade={"persist", "remove"})
     */
    private $fichingActivityType;

    /*
    * @Groups({"read:fichingacollection"})
    */
    public function getIri(): int
    {
        return '/productors/fiching-activities'. $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedate(): ?\DateTimeInterface
    {
        return $this->createdate;
    }

    public function setCreatedate(\DateTimeInterface $createdate): self
    {
        $this->createdate = $createdate;

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
