<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MonitorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Mink67\KafkaConnect\Annotations\Copy;
use App\Entity\Utils\TimestampTraitCopy;


#[Copy(resourceName: 'ot.monitor', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
/**
 * @ORM\Entity(repositoryClass=MonitorRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups": {"read:productor:monitor","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "ot"={
 *             "method"="GET",
 *             "path"="/productors/othres/monitor",
 *             "openapi_context"={
 *                  "summary"= "Voir les moniteurs"
 *              }
 *          }
 *         
 *      },
 *      itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/productors/othres/monitor/{id}",
 *             "openapi_context"={
 *                  "summary"= "Voir un moniteur"
 *              }
 *          }
 *      }
 * )
 */
class Monitor
{
    use TimestampTraitCopy;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:monitor","write:Monitor","event:kafka"})
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Productor::class, mappedBy="monitor")
     */
    private $productors;

    /**
     * @Groups({"read:productor:monitor","write:Monitor","event:kafka"})
     * @ORM\ManyToOne(targetEntity=OT::class, inversedBy="monitors")
     */
    private $ot;

    /**
     * @Groups({"read:productor:monitor","write:Monitor","event:kafka"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="monitor")
     */
    private $user;

    /**
     * @Groups({"read:productor:monitor","write:Monitor","event:kafka"})
     * @ORM\Column(type="integer")
     */
    private $goalRecordings;

    /**
     * @Groups({"read:productor:monitor","write:Monitor","event:kafka"})
     * @ORM\ManyToOne(targetEntity=Supervisor::class, inversedBy="monitors")
     */
    private $supervisorPost;

    public function __construct()
    {
        $this->productors = new ArrayCollection();
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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
            $productor->setMonitor($this);
        }

        return $this;
    }

    public function removeProductor(Productor $productor): self
    {
        if ($this->productors->removeElement($productor)) {
            // set the owning side to null (unless already changed)
            if ($productor->getMonitor() === $this) {
                $productor->setMonitor(null);
            }
        }

        return $this;
    }

    public function getOt(): ?OT
    {
        return $this->ot;
    }

    public function setOt(?OT $ot): self
    {
        $this->ot = $ot;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGoalRecordings(): ?int
    {
        return $this->goalRecordings;
    }

    public function setGoalRecordings(int $goalRecordings): self
    {
        $this->goalRecordings = $goalRecordings;

        return $this;
    }

    public function getSupervisorPost(): ?Supervisor
    {
        return $this->supervisorPost;
    }

    public function setSupervisorPost(?Supervisor $supervisorPost): self
    {
        $this->supervisorPost = $supervisorPost;

        return $this;
    }
}
