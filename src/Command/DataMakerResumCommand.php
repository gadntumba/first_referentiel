<?php

namespace App\Command;

use App\Entity\Productor;
use App\Entity\ProductorPreload;
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
    const GREEN_ECONOMY = "Economie verte";
    const INDISTRY = "Industrie legère";
    const AGRI_TRANSFORMATION = "Agro-transformation";
    const SERVICES = "Service";

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
        
        #$data = $this->productorRepository->findBy(["isNormal" => true, "isActive" => true], ["created_at_bus"=>"DESC"]);
        $data = $this->productorRepository->findBy([], ["createdAt"=>"DESC"]);

        $allData = [];

        $header = [
            "ID",
            "nom",
            "post-nom",
            "pre-nom",
            "Nom UCP",
            "sexe",
            "téléphone",
            "date naissance",
            "niveau étude",
            "taille menage",
            "lien photo profile",
            "lien photo carte",
            "liens photo activité",
            //
            "province",
            "ville",
            "commune",
            "Milieu",
            "Address",
            //
            "province activity",
            "ville activity",
            "commune activity",
            "Address activity",
            "latitude",
            "longitude",
            "altitude",
            //
            "Est formel",
            "Liens photo document",
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
            "Fumage - salaison - séchage",
            "Autre Type activité",
            "structure d'affiliation",
            "chiffre d'affaire",
            "Staff journaliers",
            "staff permenant",
            "Volontaires",
            "Déjà Financé dans un concour",
            "Déjà Financé par padepme",
            "Déjà Financé autre subvention",
            "Déjà Contracté un crédit",
            "Quelle institution" ,
            "Quel montant",
            //"Elle rencontre aucune difficultés?",
            "Elle trouve pas de formation de qualité",
            "Elle rencontre des difficulté pour le financement",
            "Elle rencontre des tracsserie",
            "Elle a des difficulté à accéder au marché",
            "Elle a des difficulté dans la production",
            "Autre difficultés",
            "L'activité est lié à la gestion et transformation dechets",
            "L'activité est lié à la fabrication des Foyer amiéliorés",
            "L'activité est lié  Recyclage",
            "L'activité est lié à quoi d'autre" ,
            "Elle vend à des individus?",
            "Elle vend à des supermarchés?",
            "Elle vend à des entreprises",
            //"Elle vend en ligne",
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
            "Opinion",
            //"Investigateur"
            //
        ];


        array_push($allData, implode(";", $header));
        $countAll = count($data);

        $res = array_filter(
            $data,
            function ($item) {
                return $item->getPhoneNumber() == "0993426124";
            }
        );

        foreach ($data as $key => $productor) 
        {
            
            /**
             * @var ProductorPreload
             */
            $preload = $productor->getProductorPreloads()->first();
            $preload = $preload?$preload:null;

            $item = $this->transform($productor);
            $docs = $item["documents"]["entrepreneurialActivities"][0]["documents"];
            //dd($item);
            //dd($this->isFormal($docs));
            $addressPhysicLine = "";
            $addressPhysicCity = "";
            $addressPhysicTown = "";
            $addressPhysicProv = "";
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
            if (isset($item["housekeeping"]["address"]["town"]["city"]["province"]["name"])) {
                $addressPhysicProv = $item["housekeeping"]["address"]["town"]["city"]["province"]["name"];
            }

            //
            $addressActivityLine = "";
            $addressActivityCity = "";
            $addressActivityTown = "";
            $addressActivityProv = "";
            //$otherData["town"] = $freeFieldData["town"];
            //$otherData["addressLine"] = $freeFieldData["addressLine"];
            //dd($item["otherData"]["town"]);

            if (isset($item["otherData"]["addressLine"])) {
                $addressActivityLine = $item["otherData"]["addressLine"];
            }
            if (isset($item["otherData"]["town"]["name"])) {
                $addressActivityTown = $item["otherData"]["town"]["name"];
            }else{
                continue;
            }
            if (isset($item["otherData"]["town"]["city"]["name"])) {
                $addressActivityCity = $item["otherData"]["town"]["city"]["name"];
            }
            if (isset($item["otherData"]["town"]["city"]["province"]["name"])) {
                $addressActivityProv = $item["otherData"]["town"]["city"]["province"]["name"];
            }
            //

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

            if (empty(trim($activityDesc)) && !empty($item["otherData"]["desc"])) {
                $activityDesc = $item["otherData"]["desc"];
            }

            if (
                empty(trim($activitySector)) && 
                !empty($item["otherData"]["desc"])
            ) 
            {
                $activitySector = $item["otherData"]["sectorAgroForestry"] == '1' ? "Agro transformation," : "";
                $activitySector = $activitySector . ($item["otherData"]["sectorIndustry"] == '1' ? " Industrie legère," : "");
                $activitySector = $activitySector . ($item["otherData"]["sectorServices"] == '1' ? " Services," : "");
                $activitySector = $activitySector . ($item["otherData"]["sectorGreeEconomy"] == '1' ? " Economie verte," : "");
                //$activityDesc = $item["otherData"]["desc"];
            }
            if (
                empty(trim($activitySector))
            ) 
            {
                //$activityDesc = $item["otherData"]["desc"];
                $activitySector = $productor->getAiActivitySector();
            }
            if (
                is_null($activitySector)
            ) 
            {
                continue;
                //$activityDesc = $item["otherData"]["desc"];
                //$activitySector = $productor->getAiActivitySector();
            }
            /*dump($productor->getId());
            dump($item["otherData"]["desc"]);
            $activities = $this->normalizer->normalize(
                $productor, 
                null, 
                [
                    'groups' => ['read:productor:activities_data']
                ]                
            );  
            dd($activities);*/
            //$freeFieldData["activities"][0];

            $linksDoc = $this->getDocsLink($docs);
            //dd($linksDoc);

            //$linksDoc["docs"];
            //$linksDoc["imgs"];

            $row = [
                strtoupper(substr($preload?->getCityEntity()->getName(), 0, 3))."-". $preload?->getId(),
                $item["personnalIdentityData"]["name"],
                $item["personnalIdentityData"]["lastName"],
                $item["personnalIdentityData"]["firstName"],
                $preload?->getName() . " " . $preload?->getLastname() . " " . $preload?->getFirstname(),
                $item["personnalIdentityData"]["sexe"],
                $item["personnalIdentityData"]["phoneNumber"],
                $item["personnalIdentityData"]["birthdate"],
                $item["personnalIdentityData"]["levelStudy"]["libelle"],
                $item["personnalIdentityData"]["householdSize"],
                //
                $item["images"]["incumbentPhoto"],
                $item["images"]["photoPieceOfIdentification"],
                implode(",",$linksDoc["imgs"]),
                //
                $addressPhysicProv,
                $addressPhysicCity,
                $addressPhysicTown,
                "Urbain",
                $addressPhysicLine,
                //
                $addressActivityProv,
                $addressActivityCity,
                $addressActivityTown,
                $addressActivityLine,
                $item["latitude"],
                $item["longitude"],
                $item["altitude"],
                $this->isFormal($docs)? "Oui":"Non",
                implode(",",$linksDoc["docs"]),

                $item["otherData"]["creationYear"],
                $activityDesc,
                //preload
                $preload?->getSector() == null? $this->convertSector($activitySector) : $preload?->getSector(),
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
                //$item["otherData"]["noDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["trainningDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["financingDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["tracaserieDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["marketAccessDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["productionDificuty"] == "1"? "OUI" : "NON",
                $item["otherData"]["otherDificuty"],
                $item["otherData"]["activityLinkwasteProcessing"] == "1"? "OUI" : "NON",
                $item["otherData"]["activityLinkImprovedStoves"] == "1"? "OUI" : "NON",
                $item["otherData"]["activityLinkRecycling"] == "1"? "OUI" : "NON",
                $item["otherData"]["otherActivityLink"],
                $item["otherData"]["indidualCustomer"] == "1"? "OUI" : "NON",
                $item["otherData"]["supermarketCustomer"] == "1"? "OUI" : "NON",
                $item["otherData"]["businessCustomer"] == "1"? "OUI" : "NON",
                //$item["otherData"]["onLineCustomer"] == "1"? "OUI" : "NON",
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
                $item["otherData"]["instigatorOpinion"],
                //$productor->getInvestigatorId()
                //productor
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
    function getDocsLink(array $docements) : array {
        $docs = [];
        $imgs = [];
        //dump($docements);

        foreach ($docements as $key => $doc) {
            if(
                !(strpos($doc["path"], "http") === false) && 
                !(($doc["documentType"]["id"] == 6) ||
                ($doc["documentType"]["id"] == 7) )
            ) 
            {
                array_push($docs, $doc["path"]);
                //dump($doc["path"]);
            }else if(!(strpos($doc["path"], "http") === false)) {
                array_push($imgs, $doc["path"]);
            }
        }
        return [
            "docs" => $docs,
            "imgs" => $imgs,
        ];
    }

    function convertSector(string $text)  {

        $matching = [
           " Economie verte," => self::GREEN_ECONOMY,
            "Industrie legère,"  => self::INDISTRY,
            "Industrie legère, Economie verte,"  => self::GREEN_ECONOMY, 
            "Industrie legère, Services," => self::INDISTRY,
            "Industrie legère, Services, Economie verte," => self::GREEN_ECONOMY,
            "Services, Economie verte,"  => self::GREEN_ECONOMY,
           "agri-transformation " => self::AGRI_TRANSFORMATION,
           "agro transformation " => self::AGRI_TRANSFORMATION,
           "Agro transformation," => self::AGRI_TRANSFORMATION,
           "Agro transformation, Economie verte,"  => self::GREEN_ECONOMY,
           "Agro transformation, Industrie legère," => self::AGRI_TRANSFORMATION,
           "Agro transformation, Industrie legère, Economie verte,"  => self::GREEN_ECONOMY,
           "Agro transformation, Industrie legère, Services," => self::AGRI_TRANSFORMATION,
           "Agro transformation, Industrie legère, Services, Economie verte,"  => self::GREEN_ECONOMY,
           "Agro transformation, Services," => self::AGRI_TRANSFORMATION,
           "Agro transformation, Services, Economie verte," => self::GREEN_ECONOMY,
           "Agro-transformati"  => self::AGRI_TRANSFORMATION,
           "Agro-transformation"  => self::AGRI_TRANSFORMATION,
           "Agro-transformation "  => self::AGRI_TRANSFORMATION,
           "Agroalimentaire"  => self::AGRI_TRANSFORMATION,
           "agros-transformation"  => self::AGRI_TRANSFORMATION,
           "agros-transformation "  => self::AGRI_TRANSFORMATION,
           "agrotransformation"  => self::AGRI_TRANSFORMATION,
           "agrotransformation "  => self::AGRI_TRANSFORMATION,
           "Artisanat" => self::INDISTRY,
           "Arts" => self::SERVICES,
           "Boulangerie/pâtisserie "   => self::SERVICES,
           "Commerce"   => self::SERVICES,
           "Economie verte"  => self::GREEN_ECONOMY,
           "Économie verte"  => self::GREEN_ECONOMY,
           "économie verte "  => self::GREEN_ECONOMY,
           "Fabrication de produits pharmaceutiques naturels/cosmétiques naturels"   => self::INDISTRY,
           "Imdustrie légère "   => self::INDISTRY,
           "Indistrie légère "   => self::INDISTRY,
           "industrie"   => self::INDISTRY,
           "industrie "   => self::INDISTRY,
           "Industrie légère"   => self::INDISTRY,
           "Industrie légère "   => self::INDISTRY,
           "Industrie légère et "   => self::INDISTRY,
           "Industries légere"   => self::INDISTRY,
           "l’industrie légère"   => self::INDISTRY, 
           "Restauration "   => self::INDISTRY,
           "service"   => self::SERVICES,
           "Service "   => self::SERVICES,
           "service "   => self::SERVICES,
           "Service et restauration "   => self::SERVICES,
           "Services"   => self::SERVICES,
           "Services "   => self::SERVICES,
           "Textiles/chaussures"  => self::INDISTRY,
           ""  => '',
           "transformation" => self::AGRI_TRANSFORMATION
        ];

        $matching2 = [];

        foreach ($matching as $key => $item) {
            $newKey =  strtolower(trim($this->fctRetirerAccents($key)));
            $matching2[$newKey] = $item;
        }
        //dd($matching2);
        return $matching2[strtolower(trim($this->fctRetirerAccents($text)))];

    }

    private function fctRetirerAccents($varMaChaine)
    {
        $search  = array(',','À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        //Préférez str_replace à strtr car strtr travaille directement sur les octets, ce qui pose problème en UTF-8
        $replace = array('', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');

        $varMaChaine = str_replace($search, $replace, $varMaChaine);
        return $varMaChaine; //On retourne le résultat
    }

}
