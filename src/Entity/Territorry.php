<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TerritorryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TerritorryRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:territorycollection"}},
 *      collectionOperations={
 *         "city-vue"={
 *             "method"="GET",
 *             "path"="/location/territories",
 *             "openapi_context"={
 *                  "summary"= "Voir les territoires"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "path"="/location/territories",
 *             "denormalization_context"={"groups":{"write:Territory"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter les territoires"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "territory-update"={
 *            "denormalization_context"={"groups":{"write:Territory"}},
 *            "method"="PATCH",
 *             "path"="/location/territories/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un territoire"
 *              }
 *          } 
 *       } 
 * )
 */
class Territorry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:territorycollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write:Territory","read:territorycollection"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Sector::class, mappedBy="territorry")
     */
    private $sectors;

    /**
     * @ORM\ManyToOne(targetEntity=Province::class, inversedBy="territorries")
     * @Groups({"read:territorycollection"})
     */
    private $province;

    public function __construct()
    {
        $this->sectors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Sector[]
     */
    public function getSectors(): Collection
    {
        return $this->sectors;
    }

    public function addSector(Sector $sector): self
    {
        if (!$this->sectors->contains($sector)) {
            $this->sectors[] = $sector;
            $sector->setTerritorry($this);
        }

        return $this;
    }

    public function removeSector(Sector $sector): self
    {
        if ($this->sectors->removeElement($sector)) {
            // set the owning side to null (unless already changed)
            if ($sector->getTerritorry() === $this) {
                $sector->setTerritorry(null);
            }
        }

        return $this;
    }

    public function getProvince(): ?Province
    {
        return $this->province;
    }

    public function setProvince(?Province $province): self
    {
        $this->province = $province;

        return $this;
    }
}
