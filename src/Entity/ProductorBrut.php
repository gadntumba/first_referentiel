<?php

namespace App\Entity;

//use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductorBrutRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Api\ProductorBrutController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductorBrutRepository::class)]
#[ApiResource(
    collectionOperations:[
            "post" => [
                "method" => "POST",
                 "path" => "/productor_bruts",
                 "controller" => ProductorBrutController::class
            ]
    ],
    itemOperations:["get"]
)]
class ProductorBrut
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

    #[ORM\Column(length: 255)]
    private ?string $investigatorId = null;

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

    public function getInvestigatorId(): ?string
    {
        return $this->investigatorId;
    }

    public function setInvestigatorId(string $investigatorId): static
    {
        $this->investigatorId = $investigatorId;

        return $this;
    }
}
