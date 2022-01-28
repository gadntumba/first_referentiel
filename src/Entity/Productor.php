<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ProductorRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=ProductorRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups": {"read:collection"}},
 *     collectionOperations={
 *         "get",
 *         "post"= {
 *              "validation_groups"={Productor::class, "validationGroups"}
 *          }
 *      },
 *     itemOperations={
 *         "put"={
 *            "denormalization_context"={"groups":{"write:Productor"}}  
 *          },
 *         "delete",
 *         "get"={
 *             "normalization_context"={"groups":{"read:collection", "read:item", "read:Productor"}}
 *     }
 *          }
 * )
 */
class Productor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:Productor"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:Productor","write:Productor","read:collection"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:collection","write:Productor"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write:Productor","read:item","read:collection"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:item","read:collection","write:Productor"})
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write:Productor","read:collection"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="date")
     * @Groups({"write:Productor","read:collection"})
     */
    private $birthdate;

    /**
     *  @Groups({"write:Productor","read:collection"})
     * @ORM\Column(type="string", length=255)
     */
    private $nui;

    public static function validationGroups(self $productor){
        return ['create:Productor'];
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

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getNui(): ?string
    {
        return $this->nui;
    }

    public function setNui(string $nui): self
    {
        $this->nui = $nui;

        return $this;
    }
}
