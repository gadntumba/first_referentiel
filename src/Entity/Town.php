<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TownRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Mink67\KafkaConnect\Annotations\Copyable;

//#[Copyable(resourceName: 'location.town', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
#[ORM\Entity(repositoryClass:TownRepository::class)]
#[ORM\HasLifecycleCallbacks()]
/**
 * @ApiResource(
 *      normalizationContext={"groups": {"read:towncollection","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "town-vue"={
 *             "method"="GET",
 *             "path"="/location/towns",
 *             "openapi_context"={
 *                  "summary"= "Voir les communes"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "path"="/location/towns",
 *             "denormalization_context"={"groups":{"write:Town"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter les communes"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "town-update"={
 *            "denormalization_context"={"groups":{"write:Town"}},
 *            "method"="PATCH",
 *             "path"="/location/towns/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier une commune"
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
class Town
{

    use TimestampTrait;
    
    /**
     * 
     * @Groups({"read:productor:house_keeping","read:towncollection","event:kafka", "read:productor:activities_data"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    /**
     * 
     * @Groups({"read:productor:house_keeping","write:Town","read:towncollection", "event:kafka", "read:productor:activities_data"})
     * @Assert\NotBlank
     * @Assert\Length(
     *  min = 3,
     *  max = 50,
     *  groups={"postValidation"}  
     *)
     */
    #[ORM\Column(type:"string", length:255)]
    private $name;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:Address::class, mappedBy:"town")]
    private $addresses;

    /**
     * @Groups({"read:productor:house_keeping","write:Town","read:towncollection", "event:kafka", "read:productor:activities_data"})
     * 
     */
    #[ORM\ManyToOne(targetEntity:City::class, inversedBy:"towns")]
    private $city;

    #[ORM\OneToMany(mappedBy: 'town', targetEntity: EntrepreneurialActivity::class)]
    private Collection $entrepreneurialActivities;

    #[ORM\OneToMany(mappedBy: 'town', targetEntity: DownloadItemProductor::class)]
    private Collection $downloadItemProductors;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->entrepreneurialActivities = new ArrayCollection();
        $this->downloadItemProductors = new ArrayCollection();
        
    }

    public static function validationGroups(self $town){
        return ['create:Town'];
    }

    /*
    * @Groups({"read:citycollection"})
    */
    public function getIri(): string
    {
        return '/api/towns/'. $this->id;
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
            $address->setTown($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getTown() === $this) {
                $address->setTown(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, EntrepreneurialActivity>
     */
    public function getEntrepreneurialActivities(): Collection
    {
        return $this->entrepreneurialActivities;
    }

    public function addEntrepreneurialActivity(EntrepreneurialActivity $entrepreneurialActivity): static
    {
        if (!$this->entrepreneurialActivities->contains($entrepreneurialActivity)) {
            $this->entrepreneurialActivities->add($entrepreneurialActivity);
            $entrepreneurialActivity->setTown($this);
        }

        return $this;
    }

    public function removeEntrepreneurialActivity(EntrepreneurialActivity $entrepreneurialActivity): static
    {
        if ($this->entrepreneurialActivities->removeElement($entrepreneurialActivity)) {
            // set the owning side to null (unless already changed)
            if ($entrepreneurialActivity->getTown() === $this) {
                $entrepreneurialActivity->setTown(null);
            }
        }

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
            $downloadItemProductor->setTown($this);
        }

        return $this;
    }

    public function removeDownloadItemProductor(DownloadItemProductor $downloadItemProductor): static
    {
        if ($this->downloadItemProductors->removeElement($downloadItemProductor)) {
            // set the owning side to null (unless already changed)
            if ($downloadItemProductor->getTown() === $this) {
                $downloadItemProductor->setTown(null);
            }
        }

        return $this;
    }
}
