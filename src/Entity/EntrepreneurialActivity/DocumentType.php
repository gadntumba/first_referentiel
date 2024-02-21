<?php

namespace App\Entity\EntrepreneurialActivity;

#use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\DocumentBrut;
use App\Repository\EntrepreneurialActivity\DocumentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DocumentTypeRepository::class)]
#[ApiResource(
    normalizationContext:["groups" => ["read:doc_type"]]
)]
class DocumentType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read:doc_type", "read:producer:document"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Groups(["read:doc_type", "read:producer:document"])]
    private ?string $wording = null;

    #[ORM\OneToMany(mappedBy: 'documentType', targetEntity: Document::class)]
    private Collection $documents;

    #[ORM\OneToMany(mappedBy: 'documentType', targetEntity: DocumentBrut::class)]
    private Collection $documentBruts;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->documentBruts = new ArrayCollection();
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
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setDocumentType($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getDocumentType() === $this) {
                $document->setDocumentType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DocumentBrut>
     */
    public function getDocumentBruts(): Collection
    {
        return $this->documentBruts;
    }

    public function addDocumentBrut(DocumentBrut $documentBrut): static
    {
        if (!$this->documentBruts->contains($documentBrut)) {
            $this->documentBruts->add($documentBrut);
            $documentBrut->setDocumentType($this);
        }

        return $this;
    }

    public function removeDocumentBrut(DocumentBrut $documentBrut): static
    {
        if ($this->documentBruts->removeElement($documentBrut)) {
            // set the owning side to null (unless already changed)
            if ($documentBrut->getDocumentType() === $this) {
                $documentBrut->setDocumentType(null);
            }
        }

        return $this;
    }
}
