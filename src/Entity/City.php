<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Mink67\KafkaConnect\Annotations\Copyable;

//#[Copyable(resourceName: 'location.city', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
#[ORM\Entity(repositoryClass:CityRepository::class)]
#[ORM\HasLifecycleCallbacks()]
/**
 * @ApiResource(
 *      normalizationContext={"groups": {"read:citycollection","timestamp:read","slug:read"}},
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
 * @UniqueEntity(
 *     fields= "name",
 *     errorPath="name",
 *     message="Ce nom existe déjà"
 * )
 */
class City
{

    use TimestampTrait;
    
    /**
     * 
     * @Groups({"read:productor:house_keeping","read:citycollection","read:towncollection", "event:kafka", "read:productor:activities_data"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    #[Groups(["read:organization"])]
    private $id;

    /**
     * @Groups({
     *      "read:productor:house_keeping",
     *      "write:City",
     *      "read:citycollection",
     *      "read:citycollection",
     *      "read:towncollection", 
     *      "event:kafka", "read:productor:activities_data"
     * })
     */
    #[ORM\Column(type:"string", length:255)]
    #[Groups(["read:organization"])]
    private $name;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:Town::class, mappedBy:"city")]
    private $towns;

    /**
     * @Groups({
     *      "read:productor:house_keeping",
     *      "write:City",
     *      "read:citycollection",
     *      "read:towncollection",
     *      "event:kafka", "read:productor:activities_data"
     * })
     * 
     */
    #[ORM\ManyToOne(targetEntity:Province::class, inversedBy:"cities")]
    private $province;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:Supervisor::class, mappedBy:"city", cascade:["persist"])]
    private $supervisors;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Organization::class)]
    private Collection $organizations;

    #[ORM\Column(nullable: true)]
    private ?array $someoneCanNotValidator = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: DownloadItemProductor::class)]
    private Collection $downloadItemProductors;

    #[ORM\OneToMany(mappedBy: 'cityEntity', targetEntity: ProductorPreload::class)]
    private Collection $productorPreloads;

    public function __construct()
    {
        $this->towns = new ArrayCollection();
        $this->supervisors = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        $this->downloadItemProductors = new ArrayCollection();
        $this->productorPreloads = new ArrayCollection();
        
    }

    /*
    * @Groups({"read:citycollection"})
    */
    public static function validationGroups(self $city){
        return ['create:City'];
    }
    public function getIri(): string
    {
        return '/api/cities/'. $this->id;
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
            $supervisor->setCity($this);
        }

        return $this;
    }

    public function removeSupervisor(Supervisor $supervisor): self
    {
        if ($this->supervisors->removeElement($supervisor)) {
            // set the owning side to null (unless already changed)
            if ($supervisor->getCity() === $this) {
                $supervisor->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): static
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations->add($organization);
            $organization->setCity($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): static
    {
        if ($this->organizations->removeElement($organization)) {
            // set the owning side to null (unless already changed)
            if ($organization->getCity() === $this) {
                $organization->setCity(null);
            }
        }

        return $this;
    }

    public function getSomeoneCanNotValidator(): ?array
    {
        return $this->someoneCanNotValidator?$this->someoneCanNotValidator:[];
    }

    public function setSomeoneCanNotValidator(?array $someoneCanNotValidator): static
    {
        $this->someoneCanNotValidator = $someoneCanNotValidator;

        return $this;
    }

    /**
     * @return Collection<int, DownloadItemProductor>
     */
    public function getDownloadItemProductors(): Collection
    {
        return $this->downloadItemProductors;
    }

    public function addDownloadItemProductor(DownloadItemProductor $downloadItemProductor): static
    {
        if (!$this->downloadItemProductors->contains($downloadItemProductor)) {
            $this->downloadItemProductors->add($downloadItemProductor);
            $downloadItemProductor->setCity($this);
        }

        return $this;
    }

    public function removeDownloadItemProductor(DownloadItemProductor $downloadItemProductor): static
    {
        if ($this->downloadItemProductors->removeElement($downloadItemProductor)) {
            // set the owning side to null (unless already changed)
            if ($downloadItemProductor->getCity() === $this) {
                $downloadItemProductor->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductorPreload>
     */
    public function getProductorPreloads(): Collection
    {
        return $this->productorPreloads;
    }

    public function addProductorPreload(ProductorPreload $productorPreload): static
    {
        if (!$this->productorPreloads->contains($productorPreload)) {
            $this->productorPreloads->add($productorPreload);
            $productorPreload->setCityEntity($this);
        }

        return $this;
    }

    public function removeProductorPreload(ProductorPreload $productorPreload): static
    {
        if ($this->productorPreloads->removeElement($productorPreload)) {
            // set the owning side to null (unless already changed)
            if ($productorPreload->getCityEntity() === $this) {
                $productorPreload->setCityEntity(null);
            }
        }

        return $this;
    }
}
