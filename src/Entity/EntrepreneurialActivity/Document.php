<?php

namespace App\Entity\EntrepreneurialActivity;

#use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\EntrepreneurialActivity;
use App\Repository\EntrepreneurialActivity\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
//#[Groups(["read:producer:document"])]
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ApiResource]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:document", "read:producer:document"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read:document", "read:producer:document"])]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[Groups(["read:document", "read:producer:document"])]
    private ?DocumentType $documentType = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?EntrepreneurialActivity $activity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getDocumentType(): ?DocumentType
    {
        return $this->documentType;
    }

    public function setDocumentType(?DocumentType $documentType): static
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getActivity(): ?EntrepreneurialActivity
    {
        return $this->activity;
    }

    public function setActivity(?EntrepreneurialActivity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }
}
