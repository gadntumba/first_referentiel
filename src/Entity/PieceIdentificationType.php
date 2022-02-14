<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PieceIdentificationTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * 
 *      normalizationContext={"groups": {"read:piece_identification_type"}},
 *      collectionOperations={
 *         "piece_Identificatiin_type-vue"={
 *             "method"="GET",
 *             "path"="/piece_identification_type",
 *             "openapi_context"={
 *                  "summary"= "Voir les niveaux d'études"
 *              }
 *          },
 *         "post"={
 *             "method"="POST",
 *             "path"="/piece_identification_type",
 *             "denormalization_context"={"groups":{"write:piece_identification_type"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un niveau d'étude"
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
 *                  "summary"= "Modifier un niveau d'étude"
 *              }
 *          } 
 *       }
 * )
 * @ORM\Entity(repositoryClass=PieceIdentificationTypeRepository::class)
 */
class PieceIdentificationType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:piece_identification_type"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write:piece_identification_type","read:piece_identification_type"})
     */
    private $libelle;

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
}
