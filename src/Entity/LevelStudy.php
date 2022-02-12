<?php

namespace App\Entity;

use App\Repository\LevelStudyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LevelStudyRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:levelstudycollection"}},
 *      collectionOperations={
 *         "level-study-vue"={
 *             "method"="GET",
 *             "path"="/level-study",
 *             "openapi_context"={
 *                  "summary"= "Voir les niveaux d'études"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "path"="/level-study",
 *             "denormalization_context"={"groups":{"write:LevelStudy"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un niveau d'étude"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "level-study-update"={
 *            "denormalization_context"={"groups":{"write:LevelStudy"}},
 *            "method"="PATCH",
 *             "path"="/level-study/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un niveau d'étude"
 *              }
 *          } 
 *       }
 * )
 * 
 */
class LevelStudy
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:levelstudycollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write:LevelStudy","read:levelstudycollection"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Productor::class, mappedBy="LevelStudy")
     */
    private $productors;

    public function __construct()
    {
        $this->productors = new ArrayCollection();
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
     * @return Collection|Productor[]
     */
    public function getProductors(): Collection
    {
        return $this->productors;
    }

    public function addProductor(Productor $productor): self
    {
        if (!$this->productors->contains($productor)) {
            $this->productors[] = $productor;
            $productor->setLevelStudy($this);
        }

        return $this;
    }

    public function removeProductor(Productor $productor): self
    {
        if ($this->productors->removeElement($productor)) {
            // set the owning side to null (unless already changed)
            if ($productor->getLevelStudy() === $this) {
                $productor->setLevelStudy(null);
            }
        }

        return $this;
    }
}
