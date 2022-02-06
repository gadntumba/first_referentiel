<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SectorRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:sectorcollection"}},
 *      collectionOperations={
 *         "sector-vue"={
 *             "method"="GET",
 *             "path"="/location/sectors",
 *             "openapi_context"={
 *                  "summary"= "Voir les secteurs"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "path"="/location/sectors",
 *             "denormalization_context"={"groups":{"write:Sector"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un secteur"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "sector-update"={
 *            "denormalization_context"={"groups":{"write:Sector"}},
 *            "method"="PATCH",
 *             "path"="/location/sectors/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un secteur"
 *              }
 *          } 
 *       } 
 * )
 */
class Sector
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:sectorcollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write:Sector","read:sectorcollection"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="sector")
     */
    private $addresses;

    /**
     * @ORM\ManyToOne(targetEntity=Territorry::class, inversedBy="sectors")
     */
    private $territorry;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
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
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setSector($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getSector() === $this) {
                $address->setSector(null);
            }
        }

        return $this;
    }

    public function getTerritorry(): ?Territorry
    {
        return $this->territorry;
    }

    public function setTerritorry(?Territorry $territorry): self
    {
        $this->territorry = $territorry;

        return $this;
    }
}
