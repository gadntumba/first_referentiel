<?php

namespace App\Entity\EntrepreneurialActivity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\EntrepreneurialActivity;
use App\Repository\EntrepreneurialActivity\LegalStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LegalStatusRepository::class)]
#[ApiResource]
class LegalStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $wording = null;

    #[ORM\OneToMany(mappedBy: 'legalStatus', targetEntity: EntrepreneurialActivity::class)]
    private Collection $entrepreneurialActivities;

    public function __construct()
    {
        $this->entrepreneurialActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): static
    {
        $this->wording = $wording;

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
            $entrepreneurialActivity->setLegalStatus($this);
        }

        return $this;
    }

    public function removeEntrepreneurialActivity(EntrepreneurialActivity $entrepreneurialActivity): static
    {
        if ($this->entrepreneurialActivities->removeElement($entrepreneurialActivity)) {
            // set the owning side to null (unless already changed)
            if ($entrepreneurialActivity->getLegalStatus() === $this) {
                $entrepreneurialActivity->setLegalStatus(null);
            }
        }

        return $this;
    }
}
