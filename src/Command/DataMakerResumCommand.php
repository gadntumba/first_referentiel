<?php

namespace App\Command;

use App\Entity\Productor;
use App\Repository\ProductorRepository;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsCommand(
    name: 'app:data-maker-resum',
    description: 'Add a short description for your command',
)]
class DataMakerResumCommand extends Command
{

    public function __construct(
        private ProductorRepository $productorRepository,
        private NormalizerInterface $normalizer,
        private ContainerInterface $container
    ) 
    {
        parent::__construct();        
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('arg1');
        $projectDir = $this->container->getParameter('kernel.project_dir');
        
        $data = $this->productorRepository->findBy(["isNormal" => true, "isActive" => true], ["created_at_bus"=>"DESC"]);

        $allData = [];

        $header = [
            "nom",
            "post-nom",
            "pre-nom",
            "sexe",
            "téléphone",
            "date naissance",
            "niveau étude",
            "taille menage",
            "lien photo profile",
            "lien photo carte",
            //
            "ville",
            "commune",
            "Address",
            //
            "ville activity",
            "commune activity",
            "Address activity",
            "latitude",
            "longitude",
            "altitude",
            //
            "Est formel",
            "Date de creation",
            "description",
            "secteur",
            "Etat civile",
            "type carte",
            "numero carte ID",
            "status legal",
            /*"agro alimentaire",
            "Industrie legère",
            "Services" ,
            "Economie verte",*/
            "Autres Secteur",
            /*"AI Secteur",
            "AI Description",*/
            "Transformation des fruit et Legume",
            "Production des jus",
            "Condiments",
            "Fumage, salaison, séchage",
            "Autre Type activité",
            "structure d'affiliation",
            "chiffre d'affaire",
            "Staff journaliers",
            "staff permenant",
            "membre de famille",
            "Déjà Financé dans un concour",
            "Déjà Financé par padepme",
            "Déjà Financé autre subvention",
            "Déjà Contracté un crédit",
            "Quelle institution" ,
            "Quel montant",
            "Elle rencontre aucune difficultés?",
            "Elle trouve pas de formation de qualité",
            "Elle rencontre des difficulté pour le financement",
            "Elle rencontre des tracsserie",
            "Elle a des difficulté à accéder au marché",
            "Elle a des difficulté dans la production",
            "otherDificuty",
            "L'activité est lié à la gestion et transformation dechets",
            "L'activité est lié à la fabrication des Foyer amiéliorés",
            "L'activité est lié  Recyclage",
            "L'activité est lié à quoi d'autre" ,
            "Elle vend à des individus?",
            "Elle vend à des supermarchés?",
            "Elle vend à des entreprises",
            "Elle vend en ligne",
            "Est-ce un marchand ambulant ?",
            "autres clients",
            "Elle a la vision d'avoir plusieur antennes?",
            "Elle a la vision des diversifier les clients",
            "Elle a la vision d'utiliser des emballages pro?",
            "Elle a la vision d'augmenté le chiffre d'affaire?",
            "Elle a la vision de monter une usine?",
            "Autres vision" ,
            "Le nom de la personne à contacter",
            "Le numéro de la personne à contacter",
            "Addresse de la personne à contacté",
            "Opinion"
            //
        ];

        array_push($allData, implode(";", $header));
        $countAll = count($data);

        foreach ($data as $key => $productor) 
        {
            $item = $this->transform($productor);
            $docs = $item["documents"]["entrepreneurialActivities"][0]["documents"];
            //dd($item);
            //dd($this->isFormal($docs));
            $addressPhysicLine = "";
            $addressPhysicCity = "";
            $addressPhysicTown = "";
            //
            if (isset($item["housekeeping"]["address"]["line"])) {
                $addressPhysicLine = $item["housekeeping"]["address"]["line"];
            }
            if (isset($item["housekeeping"]["address"]["town"]["name"])) {
                $addressPhysicTown = $item["housekeeping"]["address"]["town"]["name"];
            }
            if (isset($item["housekeeping"]["address"]["town"]["city"]["name"])) {
                $addressPhysicCity = $item["housekeeping"]["address"]["town"]["city"]["name"];
            }

            //
            $addressActivityLine = "";
            $addressActivityCity = "";
            $addressActivityTown = "";
            //$otherData["town"] = $freeFieldData["town"];
            //$otherData["addressLine"] = $freeFieldData["addressLine"];
            //dd($item["otherData"]["town"]);

            if (isset($item["otherData"]["addressLine"])) {
                $addressActivityLine = $item["otherData"]["addressLine"];
            }
            if (isset($item["otherData"]["town"]["name"])) {
                $addressActivityTown = $item["otherData"]["town"]["name"];
            }
            if (isset($item["otherData"]["town"]["city"]["name"])) {
                $addressActivityCity = $item["otherData"]["town"]["city"]["name"];
            }

            $isEdit = !!$productor->getEditorAgentId();
            $activitySector = "";
            $activityDesc = "";

            if (!$isEdit && isset($item["otherData"]["AIDesc"])) 
            {
                $activitySector = $item["otherData"]["AISector"];
                $activityDesc = $item["otherData"]["AIDesc"];

            }else {


              $activitySector = $item["otherData"]["sectorAgroForestry"] == '1' ? "Agro transformation," : "";
              $activitySector = $activitySector . ($item["otherData"]["sectorIndustry"] == '1' ? " Industrie legère," : "");
              $activitySector = $activitySector . ($item["otherData"]["sectorServices"] == '1' ? " Services," : "");
              $activitySector = $activitySector . ($item["otherData"]["sectorGreeEconomy"] == '1' ? " Economie verte," : "");
              $activityDesc = $item["otherData"]["desc"];
              
            }



            $row = [
                $item["personnalIdentityData"]["name"],
                $item["personnalIdentityData"]["lastName"],
                $item["personnalIdentityData"]["firstName"],
                $item["personnalIdentityData"]["sexe"],
                $item["personnalIdentityData"]["phoneNumber"],
                $item["personnalIdentityData"]["birthdate"],
                $item["personnalIdentityData"]["levelStudy"]["libelle"],
                $item["personnalIdentityData"]["householdSize"],
                //
                $item["images"]["incumbentPhoto"],
                $item["images"]["photoPieceOfIdentification"],
                //
                $addressPhysicCity,
                $addressPhysicTown,
                $addressPhysicLine,
                //
                $addressActivityCity,
                $addressActivityTown,
                $addressActivityLine,
                $item["latitude"],
                $item["longitude"],
                $item["altitude"],
                $this->isFormal($docs)? "Oui":"Non",

                $item["otherData"]["creationYear"],
                $activityDesc,
                $activitySector,
                $item["otherData"]["stateMarital"],
                $item["pieceOfIdentificationData"]["typePieceOfIdentification"]["libelle"],
                $item["pieceOfIdentificationData"]["numberPieceOfIdentification"],
                $item["otherData"]["legalStatus"],
                /*$item["otherData"]["sectorAgroForestry"],
                $item["otherData"]["sectorIndustry"],
                $item["otherData"]["sectorServices"],
                $item["otherData"]["sectorGreeEconomy"],*/
                $item["otherData"]["otherActivitySector"],
                /*$item["otherData"]["AISector"],
                $item["otherData"]["AIDesc"],*/
                $item["otherData"]["transformFruitAndVegetableActivity"] == "1"? "OUI" : "NON",
                $item["otherData"]["juiceMakerActivity"] == "1"? "OUI" : "NON",
                $item["otherData"]["condimengActivity"] == "1"? "OUI" : "NON",
                $item["otherData"]["FumageSalaisonSechageActity"] == "1"? "OUI" : "NON",
                $item["otherData"]["otherActity"],

                $item["otherData"]["affiliationStructure"],
                $item["otherData"]["turneOverAmount"],
                $item["otherData"]["journalierStaff"],
                $item["otherData"]["pernanentStaff"],
                $item["otherData"]["familyStaff"],
                $item["otherData"]["concourFinancing"] == "1"? "OUI" : "NON",
                $item["otherData"]["padepmeFinancing"] == "1"? "OUI" : "NON",
                $item["otherData"]["otherFinancing"],
                $item["otherData"]["haveCredit"] == "1"? "OUI" : "NON",
                $item["otherData"]["institutCredit"],
                $item["otherData"]["amountCredit"],
                $item["otherData"]["noDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["trainningDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["financingDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["tracaserieDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["marketAccessDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["productionDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["otherDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["activityLinkwasteProcessing"] == "1"? "OUI" : "NON",
                $item["otherData"]["activityLinkImprovedStoves"] == "1"? "OUI" : "NON",
                $item["otherData"]["activityLinkRecycling"] == "1"? "OUI" : "NON",
                $item["otherData"]["otherActivityLink"] == "1"? "OUI" : "NON",
                $item["otherData"]["indidualCustomer"] == "1"? "OUI" : "NON",
                $item["otherData"]["supermarketCustomer"] == "1"? "OUI" : "NON",
                $item["otherData"]["businessCustomer"] == "1"? "OUI" : "NON",
                $item["otherData"]["onLineCustomer"] == "1"? "OUI" : "NON",
                $item["otherData"]["dealerCustomer"] == "1"? "OUI" : "NON",
                $item["otherData"]["otherCustomer"],
                $item["otherData"]["visionManyBranches"] == "1"? "OUI" : "NON",
                $item["otherData"]["visionDiversifyClient"] == "1"? "OUI" : "NON",
                $item["otherData"]["visionUsePackaging"] == "1"? "OUI" : "NON",
                $item["otherData"]["visionInprouveTurneOver"] == "1"? "OUI" : "NON",
                $item["otherData"]["visionMakeFactory"] == "1"? "OUI" : "NON",
                $item["otherData"]["visionOther"],
                $item["otherData"]["otherContectNames"],
                $item["otherData"]["otherContectPhoneNumber"],
                $item["otherData"]["otherContectAddress"],
                $item["otherData"]["instigatorOpinion"]
                //
            ];
            //dd($row);

            foreach ($row as $k => $val) {
                $row[$k] = "\"".str_replace("\"","`",$val)."\"";
            }

            array_push($allData, implode(";", $row));
            $prec = ($key/$countAll)*100;

            dump("Pourcentage : ". $prec);

            //dd($row);
            //dd($item);
            //dd($item["otherData"]);
        }

        //$projectDir;

        file_put_contents($projectDir."/download_all.csv", implode("\n", $allData));

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    /**
     * 
     */
    private function transform(Productor $productor, bool $short=false)
    {
        $item = $productor;


        $itemArr = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:level_0']
            ]
            
        );
        $itemArr['personnalIdentityData'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:personnal_id_data']
            ]
            
        );
        $itemArr['pieceOfIdentificationData'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:piece_of_id_data']
            ]
            
        );
        $itemArr['activityData'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:activities_data']
            ]                
        );  
        $itemArr['housekeeping'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['read:productor:house_keeping']
            ]                
        )["housekeeping"];

        //""         
        
        //dd($short);
        if (
            method_exists($item, "getHousekeeping") &&
            !is_null($item->getHousekeeping())
            && !$short
        ) {
            $itemArr['housekeeping'] = $this->normalizer->normalize(
                $item->getHousekeeping(), 
                null, 
                [
                    'groups' => ['read:productor:house_keeping']
                ]
                
            );                
        }
        //'timestamp:read'
        $itemArr['timestamp'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ['timestamp:read']
            ]
            
        );


        $itemArr['images'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ["read:producer:image"]
            ]
            
        );

        $itemArr['documents'] = $this->normalizer->normalize(
            $item, 
            null, 
            [
                'groups' => ["read:producer:document"]
            ]
            
        );

        //dd(count($itemArr['activityData']["entrepreneurialActivities"]));

        if (count($itemArr['activityData']["entrepreneurialActivities"]) > 0) 
        {
            $newData = [...$itemArr['activityData']["entrepreneurialActivities"]];
            
            $freeFieldData = array_pop($newData);
            
            $otherData = [];
            $org = $productor->getOrganization();

            $otherData["desc"] = isset($freeFieldData["activities"][0])? $freeFieldData["activities"][0] :null;
            $otherData["creationYear"] = isset($freeFieldData["creationYear"])? $freeFieldData["creationYear"] :null;
            $otherData["stateMarital"] = isset($freeFieldData["activities"][2])? $freeFieldData["activities"][2] :null;
            $otherData["otherIDCard"] = isset($freeFieldData["activities"][3])? $freeFieldData["activities"][3] :null;
            $otherData["legalStatus"] = isset($freeFieldData["activities"][4])? $freeFieldData["activities"][4] :null;

            $otherData["sectorAgroForestry"] = isset($freeFieldData["activities"][5])? $freeFieldData["activities"][5] : null;
            $otherData["sectorIndustry"] = isset($freeFieldData["activities"][6])? $freeFieldData["activities"][6] : null;
            $otherData["sectorServices"] = isset($freeFieldData["activities"][7])? $freeFieldData["activities"][7] : null;
            $otherData["sectorGreeEconomy"] = isset($freeFieldData["activities"][8])? $freeFieldData["activities"][8] : null;
            $otherData["otherActivitySector"] = isset($freeFieldData["activities"][9])? $freeFieldData["activities"][9] : null;

            $otherData["AISector"] = $productor->getAiActivitySector();
            $otherData["AIDesc"] = $productor->getAiDesc();


            $otherData["transformFruitAndVegetableActivity"] = isset($freeFieldData["activities"][10])? $freeFieldData["activities"][10] : null;
            $otherData["juiceMakerActivity"] = isset($freeFieldData["activities"][11])? $freeFieldData["activities"][11] : null;
            $otherData["condimengActivity"] = isset($freeFieldData["activities"][12])? $freeFieldData["activities"][12] : null;
            $otherData["FumageSalaisonSechageActity"] = isset($freeFieldData["activities"][13])? $freeFieldData["activities"][13] : null;
            $otherData["otherActity"] = isset($freeFieldData["activities"][14])? $freeFieldData["activities"][14] : null;

            $otherData["affiliationStructure"] = isset($freeFieldData["activities"][15])? $freeFieldData["activities"][15] : null;
            $otherData["affiliationStructure"] = is_null($org)? $otherData["affiliationStructure"] : $org->getName();

            $otherData["turneOverAmount"] = isset($freeFieldData["activities"][16])? $freeFieldData["activities"][16] : null;

            $otherData["journalierStaff"] = isset($freeFieldData["activities"][17])? $freeFieldData["activities"][17] : null;
            $otherData["pernanentStaff"] = isset($freeFieldData["activities"][55])? $freeFieldData["activities"][55] : null;
            $otherData["familyStaff"] = isset($freeFieldData["activities"][56])? $freeFieldData["activities"][56] : null;

            $otherData["concourFinancing"] = isset($freeFieldData["taxes"][18])? $freeFieldData["taxes"][18] : null;
            $otherData["padepmeFinancing"] = isset($freeFieldData["taxes"][19])? $freeFieldData["taxes"][19] : null;
            $otherData["otherFinancing"] = isset($freeFieldData["taxes"][20])? $freeFieldData["taxes"][20] : null;

            $otherData["haveCredit"] = isset($freeFieldData["taxes"][21])? $freeFieldData["taxes"][21] : null;
            $otherData["institutCredit"] = isset($freeFieldData["taxes"][22])? $freeFieldData["taxes"][22] : null;
            $otherData["amountCredit"] = isset($freeFieldData["taxes"][23])? $freeFieldData["taxes"][23] : null;

            $otherData["noDificuty"] = isset($freeFieldData["taxes"][24])? $freeFieldData["taxes"][24] : null;
            $otherData["trainningDificuty"] = isset($freeFieldData["taxes"][25])? $freeFieldData["taxes"][25] : null;
            $otherData["financingDificuty"] = isset($freeFieldData["taxes"][26])? $freeFieldData["taxes"][26] : null;
            $otherData["tracaserieDificuty"] = isset($freeFieldData["taxes"][27])? $freeFieldData["taxes"][27] : null;
            $otherData["marketAccessDificuty"] = isset($freeFieldData["taxes"][28])? $freeFieldData["taxes"][28] : null;
            $otherData["productionDificuty"] = isset($freeFieldData["taxes"][29])? $freeFieldData["taxes"][29] : null;
            $otherData["otherDificuty"] = isset($freeFieldData["taxes"][30])? $freeFieldData["taxes"][30] : null;

            $otherData["activityLinkwasteProcessing"] = isset($freeFieldData["taxes"][31])? $freeFieldData["taxes"][31] : null;
            $otherData["activityLinkImprovedStoves"] = isset($freeFieldData["taxes"][32])? $freeFieldData["taxes"][32] : null;
            $otherData["activityLinkRecycling"] = isset($freeFieldData["taxes"][33])? $freeFieldData["taxes"][33] : null;
            $otherData["otherActivityLink"] = isset($freeFieldData["taxes"][34])? $freeFieldData["taxes"][34] : null;

            $otherData["indidualCustomer"] = isset($freeFieldData["activities"][35])? $freeFieldData["activities"][35] : null;
            $otherData["supermarketCustomer"] = isset($freeFieldData["activities"][36])? $freeFieldData["activities"][36] : null;
            $otherData["businessCustomer"] = isset($freeFieldData["activities"][37])? $freeFieldData["activities"][37] : null;
            $otherData["onLineCustomer"] = isset($freeFieldData["activities"][38])? $freeFieldData["activities"][38] : null;
            $otherData["dealerCustomer"] = isset($freeFieldData["activities"][39])? $freeFieldData["activities"][39] : null;
            $otherData["otherCustomer"] = isset($freeFieldData["activities"][40])? $freeFieldData["activities"][40] : null;

            $otherData["visionManyBranches"] = isset($freeFieldData["activities"][41])? $freeFieldData["activities"][41] : null;
            $otherData["visionDiversifyClient"] = isset($freeFieldData["activities"][42])? $freeFieldData["activities"][42] : null;
            $otherData["visionUsePackaging"] = isset($freeFieldData["activities"][43])? $freeFieldData["activities"][43] : null;
            $otherData["visionInprouveTurneOver"] = isset($freeFieldData["activities"][44])? $freeFieldData["activities"][44] : null;
            $otherData["visionMakeFactory"] = isset($freeFieldData["activities"][45])? $freeFieldData["activities"][45] : null;
            $otherData["visionOther"] = isset($freeFieldData["activities"][46])? $freeFieldData["activities"][46] : null;

            $otherData["otherContectNames"] = isset($freeFieldData["activities"][47])? $freeFieldData["activities"][47] : null;
            $otherData["otherContectPhoneNumber"] = isset($freeFieldData["activities"][48])? $freeFieldData["activities"][48] : null;
            $otherData["otherContectAddress"] = isset($freeFieldData["activities"][49])? $freeFieldData["activities"][49] : null;

            $otherData["instigatorOpinion"] = isset($freeFieldData["activities"][50])? $freeFieldData["activities"][50] : null;
            $otherData["town"] = $freeFieldData["town"];
            $otherData["addressLine"] = $freeFieldData["addressLine"];

            $itemArr['activityData']["entrepreneurialActivities"][0]["otherData"] = $otherData;
            $itemArr['otherData'] = $otherData;
        }
        /*if (isset($itemArr['documents']["entrepreneurialActivities"][0]["documents"])) {
            $documents = $itemArr['documents']["entrepreneurialActivities"][0]["documents"];

            $imagineCacheManager = $this->imagineCacheManager;

            $documents = array_map(
                function (array $doc) use($imagineCacheManager) {
                    $pathKey = "path";
                    $doc[$pathKey] = $imagineCacheManager->getBrowserPath($doc[$pathKey], "pic_producer");
                    return $doc;
                },
                $documents
            );
            //dd($documents);
            $itemArr['documents'] = $documents;
        }else {
            $itemArr['documents'] = [];
        }*/
        //dd($item);
        //

        //$itemArr['images'] = $this->multiPartNormalizer->normalize($item, $itemArr['images']);

        //$itemArr['photoPath'] = $this->imagineCacheManager->getBrowserPath($item->getIncumbentPhoto(), "pic_producer");

        //$uri = Uri::createFromString($itemArr['photoPath']);

        //$itemArr['photoPath'] = $this->getParameter("photo_host").$uri->getPath();
        
        $itemArr['photoPath'] = $item->getIncumbentPhoto();
        
        $itemArr['photoNormalPath'] = $item->getIncumbentPhoto();  
        
        //$taxes = $productor->;

        return $itemArr;
    }

    function isFormal(array $docs) : bool {
        $res = false;
        foreach ($docs as $key => $doc) {
            if(
                !(strpos($doc["path"], "http") === false) && 
                !(($doc["documentType"]["id"] == 6) ||
                ($doc["documentType"]["id"] == 7) )
            ) 
            {
                $res = true;
                break;
                //dump($doc["path"]);
            }
        }
        return $res;
    }

}