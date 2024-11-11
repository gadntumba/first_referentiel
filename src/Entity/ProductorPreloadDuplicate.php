<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductorPreloadDuplicateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductorPreloadDuplicateRepository::class)]
#[ApiResource]
class ProductorPreloadDuplicate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["productors:duplicate:read"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'produtorDuplicateMain')]
    #[Groups(["productors:duplicate:read"])]
    private ?ProductorPreload $main = null;

    #[ORM\ManyToOne(inversedBy: 'productorDuplicateSecondaries')]
    #[Groups(["productors:duplicate:read"])]
    private ?ProductorPreload $secondary = null;

    #[ORM\ManyToOne(inversedBy: 'productorPreloadDuplicates')]
    #[Groups(["productors:duplicate:read"])]
    private ?User $userConfirm = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["productors:duplicate:read"])]
    private ?\DateTimeInterface $confirmAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["productors:duplicate:read"])]
    private ?\DateTimeInterface $setNotDuplicateAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userConfirmIdentifier = null;

    #[ORM\Column]
    private ?float $similarity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMain(): ?ProductorPreload
    {
        return $this->main;
    }

    public function setMain(?ProductorPreload $main): static
    {
        $this->main = $main;

        return $this;
    }

    public function getSecondary(): ?ProductorPreload
    {
        return $this->secondary;
    }

    public function setSecondary(?ProductorPreload $secondary): static
    {
        $this->secondary = $secondary;

        return $this;
    }

    public function getUserConfirm(): ?User
    {
        return $this->userConfirm;
    }

    public function setUserConfirm(?User $userConfirm): static
    {
        $this->userConfirm = $userConfirm;

        return $this;
    }

    public function getConfirmAt(): ?\DateTimeInterface
    {
        return $this->confirmAt;
    }

    public function setConfirmAt(?\DateTimeInterface $confirmAt): static
    {
        $this->confirmAt = $confirmAt;

        return $this;
    }

    public function getSetNotDuplicateAt(): ?\DateTimeInterface
    {
        return $this->setNotDuplicateAt;
    }

    public function setSetNotDuplicateAt(?\DateTimeInterface $setNotDuplicateAt): static
    {
        $this->setNotDuplicateAt = $setNotDuplicateAt;

        return $this;
    }

    public function getUserConfirmIdentifier(): ?string
    {
        return $this->userConfirmIdentifier;
    }

    public function setUserConfirmIdentifier(?string $userConfirmIdentifier): static
    {
        $this->userConfirmIdentifier = $userConfirmIdentifier;

        return $this;
    }

    public function getSimilarity(): ?float
    {
        return $this->similarity;
    }

    public function setSimilarity(float $similarity): static
    {
        $this->similarity = $similarity;

        return $this;
    }
}
