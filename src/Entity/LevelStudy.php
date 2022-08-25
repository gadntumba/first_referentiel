<?php

namespace App\Entity;

use App\Repository\LevelStudyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Utils\TimestampTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:LevelStudyRepository::class)]
#[ORM\HasLifecycleCallbacks()]
/**
 * @ApiResource(
 *      normalizationContext={"groups": {"read:levelstudycollection","timestamp:read","slug:read"}},
 *      collectionOperations={
 *         "level-study-vue"={
 *             "method"="GET",
 *             "path"="/level-study",
 *             "openapi_context"={
 *                  "summary"= "Voir les niveaux d'études"
 *              }
 *          },
 *         "post"={"validation_groups":{"Default","postValidation"},
 *             "method"="POST",
 *             "path"="/level-study",
 *             "denormalization_context"={"groups":{"write:LevelStudy"}},
 *             "openapi_context"={
 *                  "summary"= "Ajouter un niveau d'étude"
 *              }
 *         }
 *      },
 *      itemOperations={
 *         "get",
 *         "level-study-update"={
 *            "denormalization_context"={"groups":{"write:LevelStudy"}},
 *            "method"="PATCH",
 *             "path"="/level-study/{id}",
 *             "openapi_context"={
 *                  "summary"= "Modifier un niveau d'étude"
 *              }
 *          } 
 *       }
 * )
 * @UniqueEntity(
 *     fields= "libelle",
 *     errorPath="libelle",
 *     message="Ce libelle existe déjà"
 * )
 */
class LevelStudy
{

    use TimestampTrait;
    
    /**
     * @Groups({"read:productor:personnal_id_data","read:levelstudycollection"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    /**
     * 
     * @Groups({"read:productor:personnal_id_data","write:LevelStudy","read:levelstudycollection"})
     * @Assert\NotBlank
     * @Assert\Length(
     *  min = 2,
     *  max = 50,
     *  groups={"postValidation"}  
     *)
     *@Assert\Length(
     *  min = 2,
     *  max = 50,
     * groups={"putValidation"}
     *)
     */
    #[ORM\Column(type:"string", length:255)]
    private $libelle;

    /**
     * 
     */
    #[ORM\OneToMany(targetEntity:Productor::class, mappedBy:"LevelStudy")]
    private $productors;

    public function __construct()
    {
        $this->productors = new ArrayCollection();
    }
    /*
    * @Groups({"read:adresscollection"})
    */

    public static function validationGroups(self $levelStudy){
        return ['create:LevelStudy'];
    }
    public function getIri(): string
    {
        return '/api/level_studies/'. $this->id;
    }

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
            $productor->setLevelStudy($this);
        }

        return $this;
    }

    public function removeProductor(Productor $productor): self
    {
        if ($this->productors->removeElement($productor)) {
            // set the owning side to null (unless already changed)
            if ($productor->getLevelStudy() === $this) {
                $productor->setLevelStudy(null);
            }
        }

        return $this;
    }
}
