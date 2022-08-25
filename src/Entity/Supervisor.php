<?php

namespace App\Entity;

use App\Repository\SupervisorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Mink67\KafkaConnect\Annotations\Copy;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTraitCopy;

#[Copy(resourceName: 'ot.supervisor', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
#[ORM\Entity(repositoryClass:SupervisorRepository::class)]
/**
 * 
 * @ApiResource()
 */
class Supervisor
{
    use TimestampTraitCopy;

    /**
     * 
     * 
     *@Groups({"read:supervisorcollection","read:feedbackcollection","event:kafka"})
     */
    #[ORM\Id]
    #[ORM\Column(type:"integer")]
    private $id;


    /**
     * 
     *@Groups({"read:supervisorcollection","read:feedbackcollection","event:kafka"})
     */
    #[ORM\Column(type:"integer")]
    private $goalRecordings;

    /**
     * 
     *@Groups({"read:supervisorcollection","read:feedbackcollection","event:kafka"})
     */
    #[ORM\ManyToOne(targetEntity:City::class, inversedBy:"supervisors")]
    private $city;

    /**
     * 
     *@Groups({"read:supervisorcollection","read:feedbackcollection","event:kafka"})
     */
    #[ORM\ManyToOne(targetEntity:Territorry::class, inversedBy:"supervisors")]
    private $territory;

    /**
     * 
     *@Groups({"read:supervisorcollection","read:feedbackcollection","event:kafka"})
     */
    #[ORM\ManyToOne(targetEntity:OT::class, inversedBy:"supervisors")]
    private $ot;

    /**
     * 
     *@Groups({"read:supervisorcollection","read:feedbackcollection","event:kafka"})
     */
    #[ORM\ManyToOne(targetEntity:User::class, inversedBy:"supervisors")]
    private $user;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:Monitor::class, mappedBy:"supervisorPost")]
    private $monitors;


    public function __construct()
    {
        $this->monitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTerritory(): ?Territorry
    {
        return $this->territory;
    }

    public function setTerritory(?Territorry $territory): self
    {
        $this->territory = $territory;

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

    /**
     * @return Collection<int, Monitor>
     */
    public function getMonitors(): Collection
    {
        return $this->monitors;
    }

    public function addMonitor(Monitor $monitor): self
    {
        if (!$this->monitors->contains($monitor)) {
            $this->monitors[] = $monitor;
            $monitor->setSupervisorPost($this);
        }

        return $this;
    }

    public function removeMonitor(Monitor $monitor): self
    {
        if ($this->monitors->removeElement($monitor)) {
            // set the owning side to null (unless already changed)
            if ($monitor->getSupervisorPost() === $this) {
                $monitor->setSupervisorPost(null);
            }
        }

        return $this;
    }
}
