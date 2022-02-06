<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:citycollection"}},
 *      collectionOperations={
 *         "city-vue"={
 *             "method"="GET",
 *             "path"="/location/cities",
 *             "openapi_context"={
 *                  "summary"= "Voir les villes"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "path"="/location/cities",
 *             "denormalization_context"={"groups":{"write:City"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter une ville"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "city-update"={
 *            "denormalization_context"={"groups":{"write:City"}},
 *            "method"="PATCH",
 *             "path"="/location/cities/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier une ville"
 *              }
 *          } 
 *       } 
 * )
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:citycollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write:City","read:citycollection"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Town::class, mappedBy="city")
     */
    private $towns;

    /**
     * @ORM\ManyToOne(targetEntity=Province::class, inversedBy="cities")
     */
    private $province;

    public function __construct()
    {
        $this->towns = new ArrayCollection();
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
     * @return Collection|Town[]
     */
    public function getTowns(): Collection
    {
        return $this->towns;
    }

    public function addTown(Town $town): self
    {
        if (!$this->towns->contains($town)) {
            $this->towns[] = $town;
            $town->setCity($this);
        }

        return $this;
    }

    public function removeTown(Town $town): self
    {
        if ($this->towns->removeElement($town)) {
            // set the owning side to null (unless already changed)
            if ($town->getCity() === $this) {
                $town->setCity(null);
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
