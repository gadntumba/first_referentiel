<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\StockRaisingActivityRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRaisingActivityRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:stockraisingcollection"}},
 *      collectionOperations={
 *         "stock-raising-activities-vue"={
 *             "method"="GET",
 *             "path"="/{id}/stock-raising-activities",
 *             "openapi_context"={
 *                  "summary"= "Voir les activités d'elevage"
 *              }
 *          },
 *         "stock-raising-activities-add"={
 *             "method"="POST",
 *             "path"="/{id}/stock-raising-activities",
 *             "denormalization_context"={"groups":{"read:StockRaisingActivity"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter une activité d'elevage"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "stock-raising-activities-update"={
 *            "denormalization_context"={"groups":{"read:StockRaisingActivity"}},
 *            "method"="PATCH",
 *             "path"="/{id}/stock-raising-activities/{stockRaisingActivity}",
 *             "openapi_context"={
 *                  "summary"= "Modifier une activité d'elevage"
 *              }
 *          } 
 *       }
 * )
 */
class StockRaisingActivity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:stockrasingcollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read:stockraisingcollection","read:StockRaisingActivity"})
     */
    private $CreateDate;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:stockraisingcollection","read:StockRaisingActivity"})
     */
    private $goal;

    /**
     * @ORM\ManyToOne(targetEntity=Productor::class, inversedBy="raisingactivity")
     */
    private $productor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->CreateDate;
    }

    public function setCreateDate(\DateTimeInterface $CreateDate): self
    {
        $this->CreateDate = $CreateDate;

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
}
