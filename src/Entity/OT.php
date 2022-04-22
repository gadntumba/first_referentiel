<?php

namespace App\Entity;

use App\Entity\Utils\TimestampTrait;
use App\Repository\OTRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Mink67\KafkaConnect\Annotations\Copy;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Utils\TimestampTraitCopy;

#[Copy(resourceName: 'ot.ot', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
/**
 * @ORM\Entity(repositoryClass=OTRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups": {"read:productor:ot","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "get",
 *         "ot"={
 *             "method"="GET",
 *             "path"="/productors/othres/ot",
 *             "openapi_context"={
 *                  "summary"= "Voir les OT"
 *              }
 *          }
 *         
 *      },
 *      itemOperations={
 *         "get",
 *         "get-producer"={
 *             "method"="GET",
 *             "path"="/productors/othres/ot/{id}",
 *             "openapi_context"={
 *                  "summary"= "Voir les OT"
 *              }
 *          }
 *      }
 * )
 */
class OT
{
    use TimestampTraitCopy;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"read:productor:ot","event:kafka"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Monitor::class, mappedBy="ot")
     */
    private $monitors;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:ot","event:kafka"})
     */
    private $entitled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read:productor:ot","event:kafka"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read:productor:ot","event:kafka"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read:productor:ot","event:kafka"})
     */
    private $rccm;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Groups({"read:productor:ot","event:kafka"})
     */
    private $goalRecordings;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="ot")
     *@Groups({"read:otcollection","write:Ot","event:kafka"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Supervisor::class, mappedBy="ot")
     */
    private $supervisors;

    /**
     * @ORM\OneToMany(targetEntity=Coordinator::class, mappedBy="ot")
     */
    private $coordinators;

    /**
     * @ORM\OneToMany(targetEntity=Province::class, mappedBy="ot")
     *@Groups({"read:otcollection","write:Ot","event:kafka"})
     */
    private $provinces;

    public function __construct()
    {
        $this->monitors = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->supervisors = new ArrayCollection();
        $this->coordinators = new ArrayCollection();
        $this->provinces = new ArrayCollection();
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
     * @return Collection|Monitor[]
     */
    public function getMonitors(): Collection
    {
        return $this->monitors;
    }

    public function addMonitor(Monitor $monitor): self
    {
        if (!$this->monitors->contains($monitor)) {
            $this->monitors[] = $monitor;
            $monitor->setOt($this);
        }

        return $this;
    }

    public function removeMonitor(Monitor $monitor): self
    {
        if ($this->monitors->removeElement($monitor)) {
            // set the owning side to null (unless already changed)
            if ($monitor->getOt() === $this) {
                $monitor->setOt(null);
            }
        }

        return $this;
    }


    public function getEntitled(): ?string
    {
        return $this->entitled;
    }

    public function setEntitled(string $entitled): self
    {
        $this->entitled = $entitled;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getRccm(): ?string
    {
        return $this->rccm;
    }

    public function setRccm(string $rccm): self
    {
        $this->rccm = $rccm;

        return $this;
    }

    public function getGoalRecordings(): ?string
    {
        return $this->goalRecordings;
    }

    public function setGoalRecordings(string $goalRecordings): self
    {
        $this->goalRecordings = $goalRecordings;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setOt($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getOt() === $this) {
                $user->setOt(null);
            }
        }

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
            $supervisor->setOt($this);
        }

        return $this;
    }

    public function removeSupervisor(Supervisor $supervisor): self
    {
        if ($this->supervisors->removeElement($supervisor)) {
            // set the owning side to null (unless already changed)
            if ($supervisor->getOt() === $this) {
                $supervisor->setOt(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Coordinator>
     */
    public function getCoordinators(): Collection
    {
        return $this->coordinators;
    }

    public function addCoordinator(Coordinator $coordinator): self
    {
        if (!$this->coordinators->contains($coordinator)) {
            $this->coordinators[] = $coordinator;
            $coordinator->setOt($this);
        }

        return $this;
    }

    public function removeCoordinator(Coordinator $coordinator): self
    {
        if ($this->coordinators->removeElement($coordinator)) {
            // set the owning side to null (unless already changed)
            if ($coordinator->getOt() === $this) {
                $coordinator->setOt(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Province>
     */
    public function getProvinces(): Collection
    {
        return $this->provinces;
    }

    public function addProvince(Province $province): self
    {
        if (!$this->provinces->contains($province)) {
            $this->provinces[] = $province;
            $province->setOt($this);
        }

        return $this;
    }

    public function removeProvince(Province $province): self
    {
        if ($this->provinces->removeElement($province)) {
            // set the owning side to null (unless already changed)
            if ($province->getOt() === $this) {
                $province->setOt(null);
            }
        }

        return $this;
    }

    /**
     * 
     */
    public function __toString()    
    {
        return $this->getEntitled(). " " . $this->getId();
    }
}
