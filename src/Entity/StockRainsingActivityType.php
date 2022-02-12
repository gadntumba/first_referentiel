<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\StockRainsingActivityTypeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRainsingActivityTypeRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:stocktypecollection"}},
 *      collectionOperations={
 *         "stock-rainsing-activities-types-vue"={
 *             "method"="GET",
 *             "path"="/stock-rainsing-activities/types",
 *             "openapi_context"={
 *                  "summary"= "Voir les types d'elevage"
 *              }
 *          },
 *         "stock-rainsing-activities-types-add"={
 *             "method"="POST",
 *             "path"="/stock-rainsing-activities/types",
 *             "denormalization_context"={"groups":{"read:StockRainsingActivityType"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un type d'elevage"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "stock-rainsing-activities-types-update"={
 *            "denormalization_context"={"groups":{"read:StockRainsingActivityType"}},
 *            "method"="PATCH",
 *             "path"="/stock-rainsing-activities/types/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un type d'elevage"
 *              }
 *          } 
 *       }
 * )
 */
class StockRainsingActivityType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:stocktypecollection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:stocktypecollection","read:StockRainsingActivityType"})
     */
    private $libelle;

    public function getId(): ?int
    {
        return $this->id;
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
