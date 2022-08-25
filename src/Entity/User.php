<?php

namespace App\Entity;

use App\Entity\Utils\TimestampTraitCopy;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Mink67\KafkaConnect\Annotations\Copy;
use ApiPlatform\Core\Annotation\ApiResource;

#[Copy(resourceName: 'user.user', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
#[ORM\Entity(repositoryClass:UserRepository::class)]
/**
 *@ApiResource()
 * 
 */
class User
{
    use TimestampTraitCopy;
    /**
     * 
     * 
     */
    #[ORM\Id]
    #[ORM\Column(type:"integer")]
    private $id;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * 
     */
    #[ORM\Column(type:"string", length:255)]
    private $name;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     *
     */
    #[ORM\Column(type:"string", length:255)]
    private $firstName;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * 
     */
    #[ORM\Column(type:"string", length:255)]
    private $lastName;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * 
     */
    #[ORM\Column(type:"string", length:255)]
    private $sexe;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * 
     */
    #[ORM\Column(type:"string", length:255)]
    private $phoneNumber;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * 
     */
    #[ORM\Column(type:"string", length:255, nullable:"true")]
    private $email;

    /**
     * 
     */
    #[ORM\OneToOne(targetEntity:Productor::class, cascade:["persist", "remove"])]
    private $productor;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:Monitor::class, mappedBy:"user", cascade:["persist"])]
    private $monitor;

    /**
     * 
     */
    #[ORM\ManyToOne(targetEntity:OT::class, inversedBy:"users")]
    private $ot;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:Supervisor::class, mappedBy:"user", cascade:["persist"])]
    private $supervisors;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:Coordinator::class, mappedBy:"user", cascade:["persist"])]
    private $coordinators;


    public function __construct()
    {
        $this->monitor = new ArrayCollection();
        $this->supervisors = new ArrayCollection();
        $this->coordinators = new ArrayCollection();
        
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    public function getProductor(): ?Productor
    {
        return $this->productor;
    }

    public function setProductor(?Productor $productor): self
    {
        $this->productor = $productor;

        return $this;
    }

    /**
     * @return Collection<int, Monitor>
     */
    public function getMonitor(): Collection
    {
        return $this->monitor;
    }

    public function addMonitor(Monitor $monitor): self
    {
        if (!$this->monitor->contains($monitor)) {
            $this->monitor[] = $monitor;
            $monitor->setUser($this);
        }

        return $this;
    }

    public function removeMonitor(Monitor $monitor): self
    {
        if ($this->monitor->removeElement($monitor)) {
            // set the owning side to null (unless already changed)
            if ($monitor->getUser() === $this) {
                $monitor->setUser(null);
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
            $supervisor->setUser($this);
        }

        return $this;
    }

    public function removeSupervisor(Supervisor $supervisor): self
    {
        if ($this->supervisors->removeElement($supervisor)) {
            // set the owning side to null (unless already changed)
            if ($supervisor->getUser() === $this) {
                $supervisor->setUser(null);
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
            $coordinator->setUser($this);
        }

        return $this;
    }

    public function removeCoordinator(Coordinator $coordinator): self
    {
        if ($this->coordinators->removeElement($coordinator)) {
            // set the owning side to null (unless already changed)
            if ($coordinator->getUser() === $this) {
                $coordinator->setUser(null);
            }
        }

        return $this;
    }
}
