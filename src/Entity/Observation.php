<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ObservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ObservationRepository::class)]
#[ApiResource]
class Observation
{
    /**
     * @Groups({"read:observ"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @Groups({"read:observ"})
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @Groups({"read:observ"})
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * @Groups({"read:observ"})
     */
    #[ORM\ManyToOne(inversedBy: 'observations')]
    private ?Productor $productor = null;

    /**
     * @Groups({"read:observ"})
     */
    #[ORM\Column(length: 255)]
    private ?string $userId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $askAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getProductor(): ?Productor
    {
        return $this->productor;
    }

    public function setProductor(?Productor $productor): static
    {
        $this->productor = $productor;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getAskAt(): ?\DateTimeInterface
    {
        return $this->askAt;
    }

    public function setAskAt(?\DateTimeInterface $askAt): static
    {
        $this->askAt = $askAt;

        return $this;
    }
}
