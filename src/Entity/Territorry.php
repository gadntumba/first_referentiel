<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TerritorryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TerritorryRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *      normalizationContext={"groups": {"read:territorycollection","timestamp:read","slug:read"}},
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

    use TimestampTrait;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:house_keeping","read:territorycollection","read:sectorcollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:house_keeping","write:Territory","read:territorycollection","read:sectorcollection"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Sector::class, mappedBy="territorry")
     */
    private $sectors;

    /**
     * @Groups({"read:productor:house_keeping","write:Territory","read:territorycollection"})
     * @ORM\ManyToOne(targetEntity=Province::class, inversedBy="territorries")
     */
    private $province;

    /**
     * @ORM\OneToMany(targetEntity=Supervisor::class, mappedBy="territory")
     */
    private $supervisors;

    public function __construct()
    {
        $this->sectors = new ArrayCollection();
        $this->supervisors = new ArrayCollection();
    }
    /*
    * @Groups({"read:citycollection"})
    */
    public function getIri(): string
    {
        return '/api/territorries/'. $this->id;
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

    /**
     * @return Collection<int, Supervisor>
     */
    public function getSupervisors(): Collection
    {
        return $this->supervisors;
    }

    public function addSupervisor(Supervisor $supervisor): self
    {
        if (!$this->supervisors->contains($supervisor)) {
            $this->supervisors[] = $supervisor;
            $supervisor->setTerritory($this);
        }

        return $this;
    }

    public function removeSupervisor(Supervisor $supervisor): self
    {
        if ($this->supervisors->removeElement($supervisor)) {
            // set the owning side to null (unless already changed)
            if ($supervisor->getTerritory() === $this) {
                $supervisor->setTerritory(null);
            }
        }

        return $this;
    }
}
