<?php

namespace App\Entity;

use App\Entity\Utils\TimestampTraitCopy;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Mink67\KafkaConnect\Annotations\Copy;

#[Copy(resourceName: 'user.user', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    use TimestampTraitCopy;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * @ORM\Column(type="string", length=255)
     */
    private $sexe;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * @ORM\Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     *@Groups({"read:usercollection", "event:kafka"})
     * @ORM\Column(type="string", length=255, nullable="true")
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity=Productor::class, cascade={"persist", "remove"})
     */
    private $productor;

    /**
     * @ORM\OneToMany(targetEntity=Monitor::class, mappedBy="user")
     */
    private $monitor;

    /**
     * @ORM\OneToMany(targetEntity=Ot::class, mappedBy="user")
     */
    private $ot;

    public function __construct()
    {
        $this->monitor = new ArrayCollection();
        $this->ot = new ArrayCollection();
        
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

    /**
     * @return Collection<int, Ot>
     */
    public function getOt(): Collection
    {
        return $this->ot;
    }

    public function addOt(Ot $ot): self
    {
        if (!$this->ot->contains($ot)) {
            $this->ot[] = $ot;
            $ot->setUser($this);
        }

        return $this;
    }

    public function removeOt(Ot $ot): self
    {
        if ($this->ot->removeElement($ot)) {
            // set the owning side to null (unless already changed)
            if ($ot->getUser() === $this) {
                $ot->setUser(null);
            }
        }

        return $this;
    }
}
