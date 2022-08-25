<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PieceIdentificationTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\HasLifecycleCallbacks()]
#[ORM\Entity(repositoryClass:PieceIdentificationTypeRepository::class)]
/**
 * 
 * @ApiResource(
 *      normalizationContext={"groups": {"read:piece_identification_type","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "piece_Identificatiin_type-vue"={
 *             "method"="GET",
 *             "path"="/piece_identification_type",
 *             "openapi_context"={
 *                  "summary"= "Voir les types des pièces d'identités"
 *              }
 *          },
 *         "post"={"validation_groups":{"Default","postValidation"},
 *             "method"="POST",
 *             "path"="/piece_identification_type",
 *             "denormalization_context"={"groups":{"write:piece_identification_type"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un type de pièce d'identité"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "piece_Identificatiin_type-update"={
 *            "denormalization_context"={"groups":{"write:piece_identification_type"}},
 *            "method"="PATCH",
 *             "path"="/piece_identification_type/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un type de pièce d'identité"
 *              }
 *          } 
 *       }
 * )
 * @ORM\Entity(repositoryClass=PieceIdentificationTypeRepository::class)
 * @UniqueEntity(
 *     fields= "libelle",
 *     errorPath="libelle",
 *     message="Ce libelle existe déjà"
 * )
 */
class PieceIdentificationType
{

    use TimestampTrait;
    
    /**
     * @Groups({"read:productor:piece_of_id_data","read:piece_identification_type"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    /**
     * 
     * @Assert\NotNull
     * @Groups({"read:productor:piece_of_id_data","write:piece_identification_type","read:piece_identification_type"})
     */
    #[ORM\Column(type:"string", length:255)]
    private $libelle;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:Productor::class, mappedBy:"typePieceOfIdentification")]
    private $productors;

    public function __construct()
    {
        $this->productors = new ArrayCollection();
    }
    public static function validationGroups(self $pieceIdentificationType){
        return ['create:PieceIdentificationType'];
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    /*
    * @Groups({"read:adresscollection"})
    */


    public function getIri(): string
    {
        return '/api/piece_identification_types/'. $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Productor[]
     */
    public function getProductors(): Collection
    {
        return $this->productors;
    }

    public function addProductor(Productor $productor): self
    {
        if (!$this->productors->contains($productor)) {
            $this->productors[] = $productor;
            $productor->setTypePieceOfIdentification($this);
        }

        return $this;
    }

    public function removeProductor(Productor $productor): self
    {
        if ($this->productors->removeElement($productor)) {
            // set the owning side to null (unless already changed)
            if ($productor->getTypePieceOfIdentification() === $this) {
                $productor->setTypePieceOfIdentification(null);
            }
        }

        return $this;
    }
}
