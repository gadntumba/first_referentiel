<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Mink67\KafkaConnect\Annotations\Copyable;

#[Copyable(resourceName: 'location.sector', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
/**
 * @ORM\Entity(repositoryClass=SectorRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *      normalizationContext={"groups": {"read:sectorcollection","timestamp:read","slug:read"}},
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
 * @UniqueEntity(
 *     fields= "name",
 *     errorPath="name",
 *     message="Ce nom existe déjà"
 * )
 */
class Sector
{

    use TimestampTrait;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:house_keeping","read:sectorcollection", "event:kafka"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:house_keeping","write:Sector","read:sectorcollection", "event:kafka"})
     * @Assert\NotBlank
     * @Assert\Length(
     *  min = 3,
     *  max = 50,
     *  groups={"postValidation"}  
     *)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="sector")
     */
    private $addresses;

    /**
     * @Groups({"read:productor:house_keeping","write:Sector","read:sectorcollection", "event:kafka"})
     * @ORM\ManyToOne(targetEntity=Territorry::class, inversedBy="sectors")
     */
    private $territorry;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    public static function validationGroups(self $sector){
        return ['create:Sector'];
    }
    /*
    * @Groups({"read:citycollection"})
    */
    public function getIri(): string
    {
        return '/api/sectors/'. $this->id;
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
