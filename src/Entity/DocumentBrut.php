<?php

namespace App\Entity;

use App\Entity\EntrepreneurialActivity\DocumentType;
//use ApiPlatform\Metadata\ApiResource;
use App\Repository\DocumentBrutRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DocumentBrutRepository::class)]
#[ApiResource(
    collectionOperations:[
            "post" => [
                "method" => "POST",
                 "path" => "/document_bruts",  
            ],                        
    ],
    itemOperations:["get"]
)]
class DocumentBrut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private array $dataJson = [];

    #[ORM\Column(nullable: true)]
    private ?bool $isLoad = null;

    #[ORM\ManyToOne(inversedBy: 'documentBruts')]
    #[Assert\NotNull()]
    private ?DocumentType $documentType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getDataJson(): array
    {
        return $this->dataJson;
    }

    public function setDataJson(array $dataJson): static
    {
        $this->dataJson = $dataJson;

        return $this;
    }

    public function isIsLoad(): ?bool
    {
        return $this->isLoad;
    }

    public function setIsLoad(?bool $isLoad): static
    {
        $this->isLoad = $isLoad;

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
}
