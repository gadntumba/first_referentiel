<?php

namespace App\Entity;

use App\Repository\CoordinatorRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Mink67\KafkaConnect\Annotations\Copy;
use App\Entity\Utils\TimestampTraitCopy;
use Symfony\Component\Serializer\Annotation\Groups;

#[Copy(resourceName: 'ot.coordinator', groups: ['event:kafka','timestamp:read',"slugger:read"], topicName: 'sync_rna_db')]
/**
 * @ORM\Entity(repositoryClass=CoordinatorRepository::class)
 * @ApiResource()
 */
class Coordinator
{
    use TimestampTraitCopy;
    
    #[Groups(["event:kafka"])]
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="coordinators")
     */
    #[Groups(["event:kafka"])]
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=OT::class, inversedBy="coordinators")
     */
    #[Groups(["event:kafka"])]
    private $ot;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOt(): ?OT
    {
        return $this->ot;
    }

    public function setOt(?OT $ot): self
    {
        $this->ot = $ot;

        return $this;
    }
}
