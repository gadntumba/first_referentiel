<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 *          },
 *         "housekeeping"={
 *              "method"= "POST",
 *              "path"= "/productors/houseKeeping",
 *              "controller"= "ProductorHousekeeping::class",
 *              "openapi_context"={
 *                  "summary": "Création d'un producteur avec son menage"
 *              }
 *          },
 *          "productor-update"={
 *              "method"= "PATCH",
 *              "path"= "/productors/{productor}",
 *              "controller"= "ProductorController::class",
 *              "openapi_context"={
 *                  "summary": "Modifier un producteur"
 *              }
 *          },
 *          "productor-delete"={
 *              "method"= "DELETE",
 *              "path"= "/productors/{productor}",
 *              "controller"= "ProductorSupController::class",
 *              "openapi_context"={
 *                  "summary": "Suppression du logiciel du producteur"
 *              }
 *          },
 *          "level-study voir"={
 *              "method"= "GET",
 *              "path"= "/productors/level-study",
 *              "controller"= "ProductorLevelStudy::class",
 *              "openapi_context"={
 *                  "summary": "Voir les niveaux d'études possible"
 *              }
 *          },
 *          "level-study"={
 *              "method"= "POST",
 *              "path"= "/productors/level-study",
 *              "controller"= "ProductorLevelStudy::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter un niveau d'étude"
 *           },
 *          "level-study-update"={
 *              "method"= "PATCH",
 *              "path"= "/productors/level-study/{levelStudy}",
 *              "controller"= "ProductorLevelStudy::class",
 *              "openapi_context"={
 *                  "summary": "Modifier un niveau d'étude"
 *              }
 *           },
 *          "exploite-area-view"={
 *              "method"= "GET",
 *              "path"= "api/productors/exploite-area",
 *              "controller"= "ProductorExploitedArea::class",
 *              "openapi_context"={
 *                  "summary": "Voir les zones d'exploitations"
 *           },
 *           "exploite-area-create"={
 *              "method"= "POST",
 *              "path"= "api/productors/exploite-area",
 *              "controller"= "ProductorExploitedArea::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter une zone d'exploitation"
 *              }
 *           },
 *           "exploite-area-update"={
 *              "method"= "PATCH",
 *              "path"= "api/productors/exploite-area/{exploiteArea}",
 *              "controller"= "ProductorExploitedArea::class",
 *              "openapi_context"={
 *                  "summary": "Modifier une zone d'exploitation"
 *              }
 *           },
 *           "source-supply-activities-vue"={
 *              "method"= "GET",
 *              "path"= "api/productors/source-supply-activities",
 *              "controller"= "ProductorSource::class",
 *              "openapi_context"={
 *                  "summary": "Voir les sources d'approvisionnement"
 *              }
 *           },
 *           "source-supply-activities-add"={
 *              "method"= "POST",
 *              "path"= "api/productors/source-supply-activities",
 *              "controller"= "ProductorSource::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter une source d'approvisionnement"
 *              }
 *           },
 *           "source-supply-activities-update"={
 *              "method"= "¨PATCH",
 *              "path"= "api/productors/source-supply-activities/{sourceSupplyActivities]",
 *              "controller"= "ProductorSource::class",
 *              "openapi_context"={
 *                  "summary": "Modifier une source d'approvisionnement"
 *              }
 *           },
 *           "fiching-activities-vue"={
 *              "method"= "GET",
 *              "path"= "api/productors/fiching-activities/types",
 *              "controller"= "ProductorFichingActivity::class",
 *              "openapi_context"={
 *                  "summary": "Voir les types de pêche"
 *              }
 *           },
 *           "fiching-activities-add"={
 *              "method"= "POST",
 *              "path"= "api/productors/fiching-activities/types",
 *              "controller"= "ProductorFichingActivity::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter un type de pêche"
 *              }
 *           },
 *           "fiching-activities-update"={
 *              "method"= "PATCH",
 *              "path"= "api/productors/fiching-activities/types/{type}",
 *              "controller"= "ProductorFichingActivity::class",
 *              "openapi_context"={
 *                  "summary": "Modifier un type de pêche"
 *              }
 *           },
 *           "stock-rainsing-activities/types-vue"={
 *              "method"= "GET",
 *              "path"= "api/productors/stock-rainsing-activities/types",
 *              "controller"= "ProductorStockRainsingActivitiesTypes::class",
 *              "openapi_context"={
 *                  "summary": "Voir les types d'elevage"
 *              }
 *           },
 *           "stock-rainsing-activities/types-add"={
 *              "method"= "POST",
 *              "path"= "api/productors/stock-rainsing-activities/types",
 *              "controller"= "ProductorStockRainsingActivitiesTypes::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter les types d'elevage"
 *              }
 *           },
 *           "stock-rainsing-activities/types-update"={
 *              "method"= "PATCH",
 *              "path"= "api/productors/stock-rainsing-activities/types/{type}",
 *              "controller"= "ProductorStockRainsingActivitiesTypes::class",
 *              "openapi_context"={
 *                  "summary": "Modifier un type d'elevage"
 *              }
 *           },
 *           "provinces-vue"={
 *              "method"= "GET",
 *              "path"= "api/location/provinces",
 *              "controller"= "ProductorProvinces::class",
 *              "openapi_context"={
 *                  "summary": "Voir les provinces"
 *              }
 *           },
 *           "provinces-add"={
 *              "method"= "POST",
 *              "path"= "api/location/provinces",
 *              "controller"= "ProductorProvinces::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter les provinces"
 *              }
 *           },
 *           "provinces-update"={
 *              "method"= "PATCH",
 *              "path"= "api/location/provinces/{province}",
 *              "controller"= "ProductorProvinces::class",
 *              "openapi_context"={
 *                  "summary": "Modifier les provinces"
 *              }
 *           },
 *           "cities-vue"={
 *              "method"= "GET",
 *              "path"= "api/location/cities",
 *              "controller"= "ProductorCities::class",
 *              "openapi_context"={
 *                  "summary": "Voir les villes"
 *              }
 *           },
 *           "cities-add"={
 *              "method"= "POST",
 *              "path"= "api/location/cities",
 *              "controller"= "ProductorCities::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter les villes"
 *              }
 *           },
 *           "cities-update"={
 *              "method"= "PATCH",
 *              "path"= "api/location/cities/{city}",
 *              "controller"= "ProductorCities::class",
 *              "openapi_context"={
 *                  "summary": "Modifier les villes"
 *              }
 *           },
 *           "territories-vue"={
 *              "method"= "GET",
 *              "path"= "api/location/territories",
 *              "controller"= "ProductorTerritories::class",
 *              "openapi_context"={
 *                  "summary": "Voir les territoires"
 *              }
 *           },
 *           "territories-add"={
 *              "method"= "POST",
 *              "path"= "api/location/territories",
 *              "controller"= "ProductorTerritories::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter les territoires"
 *              }
 *           },
 *           "territories-update"={
 *              "method"= "PATCH",
 *              "path"= "api/location/territories/{territory}",
 *              "controller"= "ProductorTerritories::class",
 *              "openapi_context"={
 *                  "summary": "Modifier les territoires"
 *              }
 *           },
 *           "town-vue"={
 *              "method"= "GET",
 *              "path"= "api/location/towns",
 *              "controller"= "ProductorTown::class",
 *              "openapi_context"={
 *                  "summary": "Voir les territoires"
 *              }
 *           },
 *           "town-add"={
 *              "method"= "POST",
 *              "path"= "api/location/towns",
 *              "controller"= "ProductorTown::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter les territoires"
 *              }
 *           },
 *           "town-update"={
 *              "method"= "PATCH",
 *              "path"= "api/location/towns/{town}",
 *              "controller"= "ProductorTown::class",
 *              "openapi_context"={
 *                  "summary": "Modifier les territoires"
 *              }
 *           },
 *           "sectors-vue"={
 *              "method"= "GET",
 *              "path"= "api/location/sectors",
 *              "controller"= "ProductorSectors::class",
 *              "openapi_context"={
 *                  "summary": "Voir les secteurs"
 *              }
 *           },
 *           "sectors-add"={
 *              "method"= "POST",
 *              "path"= "api/location/sectors",
 *              "controller"= "ProductorSectors::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter les secteurs"
 *              }
 *           },
 *           "sectors-update"={
 *              "method"= "PATCH",
 *              "path"= "api/location/sectors/{sector}",
 *              "controller"= "ProductorSectors::class",
 *              "openapi_context"={
 *                  "summary": "Modifier les secteurs"
 *              }
 *           },
 *           "agricultural-activities-vue"={
 *              "method"= "GET",
 *              "path"= "api/productors/{productor}/agricultural-activities",
 *              "controller"= "ProductorAgriculturalActivities::class",
 *              "openapi_context"={
 *                  "summary": "Voir les activités agricoles"
 *              }
 *           },
 *           "agricultural-activities-add"={
 *              "method"= "POST",
 *              "path"= "api/productors/{productor}/agricultural-activities",
 *              "controller"= "ProductorAgriculturalActivities::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter les activités agricoles"
 *              }
 *           },
 *           "agricultural-activities-update"={
 *              "method"= "PATCH",
 *              "path"= "api/productors/{productor}/agricultural-activities/{agriculturalActivitiy}",
 *              "controller"= "ProductorAgriculturalActivities::class",
 *              "openapi_context"={
 *                  "summary": "Modifier les activités agricoles"
 *              }
 *           },
 *           "fiching-activities-vue"={
 *              "method"= "GET",
 *              "path"= "api/productors/{productor}/fiching-activities",
 *              "controller"= "ProductorFichingActivities::class",
 *              "openapi_context"={
 *                  "summary": "Voir les activités pêches"
 *              }
 *           },
 *           "fiching-activities-add"={
 *              "method"= "POST",
 *              "path"= "api/productors/{productor}/fiching-activities",
 *              "controller"= "ProductorFichingActivities::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter les activités pêches"
 *              }
 *           },
 *           "fiching-activities-update"={
 *              "method"= "PATCH",
 *              "path"= "api/productors/{productor}/fiching-activities/{fichingActivities}",
 *              "controller"= "ProductorFichingActivities::class",
 *              "openapi_context"={
 *                  "summary": "Modifier les activités pêches"
 *              }
 *           },
 *           "stock-raising-activities-vue"={
 *              "method"= "GET",
 *              "path"= "api/productors/{productor}/stock-raising-activities",
 *              "controller"= "ProductorStockRainsingActivities::class",
 *              "openapi_context"={
 *                  "summary": "Voir les activités d'elevage"
 *              }
 *           },
 *           "stock-raising-activities-add"={
 *              "method"= "POST",
 *              "path"= "api/productors/{productor}/stock-raising-activities",
 *              "controller"= "ProductorStockRainsingActivities::class",
 *              "openapi_context"={
 *                  "summary": "Ajouter les activités d'elevage"
 *              }
 *           },
 *           "stock-raising-activities-update"={
 *              "method"= "PATCH",
 *              "path"= "api/productors/{productor}/stock-raising-activities/{stockRaisingActivity}",
 *              "controller"= "ProductorStockRainsingActivities::class",
 *              "openapi_context"={
 *                  "summary": "Modidier les activités d'elevage"
 *              }
 *           }
 *          }
 *          }
 *     }
 * )
 */
class Productor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:Productor","write:Productor"})
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

    /**
     * @ORM\ManyToOne(targetEntity=LevelStudy::class, inversedBy="productors")
     * @Groups({"write:Productor","read:collection"})
     */
    private $LevelStudy;

    /**
     * @ORM\OneToMany(targetEntity=AgriculturalActivity::class, mappedBy="productor")
     * @Groups({"read:collection","write:Productor"})
     */
    private $AgriculturalActivity;

    /**
     * @ORM\OneToMany(targetEntity=NFC::class, mappedBy="productor")
     * @Groups({"read:collection","write:Productor"})
     */
    private $nfc;

    /**
     * @ORM\OneToMany(targetEntity=FichingActivity::class, mappedBy="productor")
     * @Groups({"read:collection","write:Productor"})
     */
    private $fichingactivity;

    /**
     * @ORM\OneToMany(targetEntity=StockRaisingActivity::class, mappedBy="productor")
     * @Groups({"read:collection","write:Productor"})
     */
    private $raisingactivity;

    /**
     * @ORM\ManyToOne(targetEntity=HouseKeeping::class, inversedBy="productors")
     * @Groups({"read:collection","write:Productor"})
     */
    private $housekeeping;

    /**
     * @ORM\ManyToMany(targetEntity=Smartphone::class, inversedBy="productors")
     * @Groups({"read:collection","write:Productor"})
     */
    private $smartphone;

    /**
     * @ORM\ManyToOne(targetEntity=Monitor::class, inversedBy="productors")
     * @Groups({"read:collection","write:Productor"})
     */
    private $monitor;

    /**
     * @ORM\Column(type="float")
     * @Groups({"read:collection","write:Productor"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     * @Groups({"read:collection","write:Productor"})
     */
    private $longitude;

    /**
     * @ORM\Column(type="float")
     * @Groups({"read:collection","write:Productor"})
     */
    private $altitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:collection","write:Productor"})
     */
    private $typePieceOfIdentification;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:collection","write:Productor"})
     */
    private $numberPieceOfIdentification;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:collection","write:Productor"})
     */
    private $photoPieceOfIdentification;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:collection","write:Productor"})
     */
    private $householdSize;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read:collection","write:Productor"})
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"read:collection","write:Productor"})
     */
    private $incumbentPhoto;

    public function __construct()
    {
        $this->AgriculturalActivity = new ArrayCollection();
        $this->nfc = new ArrayCollection();
        $this->fichingactivity = new ArrayCollection();
        $this->raisingactivity = new ArrayCollection();
        $this->smartphone = new ArrayCollection();
    }

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

    public function getLevelStudy(): ?LevelStudy
    {
        return $this->LevelStudy;
    }

    public function setLevelStudy(?LevelStudy $LevelStudy): self
    {
        $this->LevelStudy = $LevelStudy;

        return $this;
    }

    /**
     * @return Collection|AgriculturalActivity[]
     */
    public function getAgriculturalActivity(): Collection
    {
        return $this->AgriculturalActivity;
    }

    public function addAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if (!$this->AgriculturalActivity->contains($agriculturalActivity)) {
            $this->AgriculturalActivity[] = $agriculturalActivity;
            $agriculturalActivity->setProductor($this);
        }

        return $this;
    }

    public function removeAgriculturalActivity(AgriculturalActivity $agriculturalActivity): self
    {
        if ($this->AgriculturalActivity->removeElement($agriculturalActivity)) {
            // set the owning side to null (unless already changed)
            if ($agriculturalActivity->getProductor() === $this) {
                $agriculturalActivity->setProductor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NFC[]
     */
    public function getNfc(): Collection
    {
        return $this->nfc;
    }

    public function addNfc(NFC $nfc): self
    {
        if (!$this->nfc->contains($nfc)) {
            $this->nfc[] = $nfc;
            $nfc->setProductor($this);
        }

        return $this;
    }

    public function removeNfc(NFC $nfc): self
    {
        if ($this->nfc->removeElement($nfc)) {
            // set the owning side to null (unless already changed)
            if ($nfc->getProductor() === $this) {
                $nfc->setProductor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FichingActivity[]
     */
    public function getFichingactivity(): Collection
    {
        return $this->fichingactivity;
    }

    public function addFichingactivity(FichingActivity $fichingactivity): self
    {
        if (!$this->fichingactivity->contains($fichingactivity)) {
            $this->fichingactivity[] = $fichingactivity;
            $fichingactivity->setProductor($this);
        }

        return $this;
    }

    public function removeFichingactivity(FichingActivity $fichingactivity): self
    {
        if ($this->fichingactivity->removeElement($fichingactivity)) {
            // set the owning side to null (unless already changed)
            if ($fichingactivity->getProductor() === $this) {
                $fichingactivity->setProductor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StockRaisingActivity[]
     */
    public function getRaisingactivity(): Collection
    {
        return $this->raisingactivity;
    }

    public function addRaisingactivity(StockRaisingActivity $raisingactivity): self
    {
        if (!$this->raisingactivity->contains($raisingactivity)) {
            $this->raisingactivity[] = $raisingactivity;
            $raisingactivity->setProductor($this);
        }

        return $this;
    }

    public function removeRaisingactivity(StockRaisingActivity $raisingactivity): self
    {
        if ($this->raisingactivity->removeElement($raisingactivity)) {
            // set the owning side to null (unless already changed)
            if ($raisingactivity->getProductor() === $this) {
                $raisingactivity->setProductor(null);
            }
        }

        return $this;
    }

    public function getHousekeeping(): ?HouseKeeping
    {
        return $this->housekeeping;
    }

    public function setHousekeeping(?HouseKeeping $housekeeping): self
    {
        $this->housekeeping = $housekeeping;

        return $this;
    }

    /**
     * @return Collection|Smartphone[]
     */
    public function getSmartphone(): Collection
    {
        return $this->smartphone;
    }

    public function addSmartphone(Smartphone $smartphone): self
    {
        if (!$this->smartphone->contains($smartphone)) {
            $this->smartphone[] = $smartphone;
        }

        return $this;
    }

    public function removeSmartphone(Smartphone $smartphone): self
    {
        $this->smartphone->removeElement($smartphone);

        return $this;
    }

    public function getMonitor(): ?Monitor
    {
        return $this->monitor;
    }

    public function setMonitor(?Monitor $monitor): self
    {
        $this->monitor = $monitor;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function setAltitude(float $altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getTypePieceOfIdentification(): ?string
    {
        return $this->typePieceOfIdentification;
    }

    public function setTypePieceOfIdentification(string $typePieceOfIdentification): self
    {
        $this->typePieceOfIdentification = $typePieceOfIdentification;

        return $this;
    }

    public function getNumberPieceOfIdentification(): ?string
    {
        return $this->numberPieceOfIdentification;
    }

    public function setNumberPieceOfIdentification(string $numberPieceOfIdentification): self
    {
        $this->numberPieceOfIdentification = $numberPieceOfIdentification;

        return $this;
    }

    public function getPhotoPieceOfIdentification(): ?string
    {
        return $this->photoPieceOfIdentification;
    }

    public function setPhotoPieceOfIdentification(string $photoPieceOfIdentification): self
    {
        $this->photoPieceOfIdentification = $photoPieceOfIdentification;

        return $this;
    }

    public function getHouseholdSize(): ?int
    {
        return $this->householdSize;
    }

    public function setHouseholdSize(int $householdSize): self
    {
        $this->householdSize = $householdSize;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getIncumbentPhoto()
    {
        return $this->incumbentPhoto;
    }

    public function setIncumbentPhoto($incumbentPhoto): self
    {
        $this->incumbentPhoto = $incumbentPhoto;

        return $this;
    }
}
