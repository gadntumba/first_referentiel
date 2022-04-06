<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProvinceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Mink67\KafkaConnect\Annotations\Copyable;


#[Copyable(resourceName: 'location.province', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
#[ORM\HasLifecycleCallbacks()]
/**
 * @ORM\Entity(repositoryClass=ProvinceRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups": {"read:provincecollection","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "provinces-vue"={
 *             "method"="GET",
 *             "path"="/location/provinces",
 *             "openapi_context"={
 *                  "summary"= "Voir les provinces"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "validation_groups"={"Default","postValidation"},
 *             "path"="/location/provinces",
 *             "denormalization_context"={"groups":{"write:Province"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter une province"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "province-update"={
 *            "denormalization_context"={"groups":{"write:Province"}},
 *            "method"="PATCH",
 *             "path"="/location/provinces/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier une province"
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
class Province
{

    use TimestampTrait;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *      "read:productor:house_keeping",
     *      "read:provincecollection",
     *      "read:citycollection",
     *      "write:Territory",
     *      "read:territorycollection", 
     *      "event:kafka"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *      "read:productor:house_keeping",
     *      "write:Province","read:provincecollection",
     *      "read:citycollection",
     *      "read:territorycollection", 
     *      "event:kafka"})
     * @Assert\NotBlank
     * @Assert\Length(
     *  min = 3,
     *  max = 50,
     *  groups={"postValidation"}  
     *)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=City::class, mappedBy="province")
     */
    private $cities;

    /**
     * @ORM\OneToMany(targetEntity=Territorry::class, mappedBy="province")
     */
    private $territorries;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
        $this->territorries = new ArrayCollection();
        
    }
    public static function validationGroups(self $province){
        return ['create:Province'];
    }

    /*
    * @Groups({"read:adresscollection"})
    */
    public function getIri(): string
    {
        return '/api/provinces/'. $this->id;
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
     * @return Collection|City[]
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
            $city->setProvince($this);
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getProvince() === $this) {
                $city->setProvince(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Territorry[]
     */
    public function getTerritorries(): Collection
    {
        return $this->territorries;
    }

    public function addTerritorry(Territorry $territorry): self
    {
        if (!$this->territorries->contains($territorry)) {
            $this->territorries[] = $territorry;
            $territorry->setProvince($this);
        }

        return $this;
    }

    public function removeTerritorry(Territorry $territorry): self
    {
        if ($this->territorries->removeElement($territorry)) {
            // set the owning side to null (unless already changed)
            if ($territorry->getProvince() === $this) {
                $territorry->setProvince(null);
            }
        }

        return $this;
    }
}
