<?php

namespace App\Command;

use ApiPlatform\Core\Bridge\Symfony\Routing\IriConverter;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Entity\City;
use App\Entity\Province;
use App\Entity\Sector;
use App\Entity\Territorry;
use App\Entity\Town;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:insert-agromwinda-places', description: 'Add a short description for your command')]
class InsertAgromwindaPlacesCommand extends Command
{

    protected static $defaultName = 'app:insert-agromwinda-places';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var HttpClientInterface
     */
    private $clientHttp;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(HttpClientInterface $clientHttp, EntityManagerInterface $em) {
        $this->clientHttp = $clientHttp;
        $this->em = $em;
        
        parent::__construct();
    }

    protected function configure(): void
    {
        /*$this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;*/
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $provinces = $this->getProvinces();


        $this->createTable();

        foreach ($provinces as $key => $province) {
            
            $this->createProvince($province);

        }
        //dd($provinces);

        return Command::SUCCESS;
    }

    /**
     * 
     */
    private function getProvinces()
    {
        $client = $this->clientHttp;
        $response = $client->request('GET', 'https://agromwinda.com/api/address/province');

        $decodedPayload = $response->toArray();

        return $decodedPayload;
    }
    /**
     * 
     */
    public function getTableName()
    {
        return "config_insert_agromwinda_places";
    }
    /**
     * 
     */
    private function getConnection()
    {
        $em = $this->em;

        $conn = $em->getConnection();

        return $conn;
    }
    /**
     * 
     */
    private function createTable()
    {
        $table = $this->getTableName();

        $conn = $this->getConnection();

        $sql = " CREATE TABLE IF NOT EXISTS  $table (
                    id int AUTO_INCREMENT,
                    entity_iri varchar(255) NOT NULL,
                    app_id int NOT NULL,
                    UNIQUE (entity_iri),
                    PRIMARY KEY (id)
            )";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeStatement([/*'tableName' => $table*/]);

    }
    /**
     * @return Param
     * @param string $key
     * @throws Exception
     * 
     */
    private function getParam(string $key): ?Param
    {
        $conn = $this->getConnection();
        $table = $this->getTableName();

        $sql = "SELECT entity_iri, app_id FROM $table WHERE  entity_iri = :key";
        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery(['key' => $key]);
        
        $arrData = $resultSet->fetchAssociative();
        
        //dd($arrData);
        $param = null;

        if ($arrData && is_array($arrData) && isset($arrData["entity_iri"])) {
            $param = new Param($arrData["entity_iri"], $arrData["app_id"]);
        }

        return $param;

    }
    
    /**
     * @return self
     * @param string $key
     * @param string $value
     * @throws Exception
     */
    private function setParam(string $iri, int $appId): self
    {
        $conn = $this->getConnection();
        $table = $this->getTableName();


        $sql = "INSERT INTO $table (entity_iri, app_id) VALUES (:iri, :app_id)";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeStatement([
                            'iri' => $iri,
                            'app_id' => $appId,
                        ]);

        return $this;
        
    }
    /**
     * 
     */
    public function createProvince(array $arrProvince)
    {
        $iriProvince = "/provinces/". $arrProvince["id"];

        $param = $this->getParam($iriProvince);

        if (is_null($param)) {

            $province = new Province;
            $province->setName($arrProvince["name"]);
            $this->em->persist($province);
            
            $this->setParam($iriProvince, $province->getId());
            $name = $arrProvince["name"];
            dump("province $name : persist");

        }else {

            $province = $this->em->getRepository(Province::class)->find($param->getAppId());
            $province->setName($arrProvince["name"]);

        }
        $this->em->flush();


        if (isset($arrProvince["cities"])) {

            $cities = $arrProvince["cities"];
            //dd($cities);
            foreach ($cities as $key => $city) {
                
                $this->createCity($city, $province);

            }
            
        }

        if (isset($arrProvince["territories"])) {

            $territories = $arrProvince["territories"];
            //dd($territories);
            foreach ($territories as $key => $territory) {
                
                $this->createTerritory($territory, $province);

            }
            
        }



    }
    /**
     * 
     */
    public function createCity(array $arrCity, Province $province)
    {
        $iriCity = "/cities/". $arrCity["id"];
        //dd($arrCity);
        $param = $this->getParam($iriCity);

        if (is_null($param)) {

            $city = new City;
            $city->setName($arrCity["name"]);
            $city->setProvince($province);

            $this->em->persist($city);
            $this->setParam($iriCity, $city->getId());
            $name = $arrCity["name"];

            dump("city $name : persist");
        }else {
            $city = $this->em->getRepository(City::class)->find($param->getAppId());
            $city->setName($arrCity["name"]);

            //dd($city);

        }
        
        $this->em->flush();


        if (isset($arrCity["towns"])) {

            $towns = $arrCity["towns"];
            foreach ($towns as $key => $town) {
                
                $this->createTown($town, $city);

            }
            
        }

    }
    /**
     * 
     */
    public function createTerritory(array $arrTerritory, Province $province)
    {
        $iriTerritory = "/territories/". $arrTerritory["id"];
        //dd($arrTerritory);
        $param = $this->getParam($iriTerritory);

        if (is_null($param)) {

            $territory = new Territorry;
            $territory->setName($arrTerritory["name"]);
            $territory->setProvince($province);

            $this->em->persist($territory);
            
            $this->setParam($iriTerritory, $territory->getId());
            $name = $arrTerritory["name"];

            dump("territory $name : persist");
        }else {
            $territory = $this->em->getRepository(Territorry::class)->find($param->getAppId());
            $territory->setName($arrTerritory["name"]);

        }

        $this->em->flush();

        if (isset($arrTerritory["groupements"])) {

            $groupements = $arrTerritory["groupements"];
            //dd($groupements);

            foreach ($groupements as $key => $groupement) {
                
                $this->createSector($groupement, $territory);

            }
            
        }

        //dd($param);

    }
    /**
     * 
     */
    public function createTown(array $arrTown, City $city)
    {
        $iriTown = "/towns/". $arrTown["id"];
        //dd($arrTown);
        $param = $this->getParam($iriTown);

        if (is_null($param)) {

            $town = new Town;
            $town->setName($arrTown["name"]);
            $town->setCity($city);

            $this->em->persist($town);
            
            $this->setParam($iriTown, $town->getId());
            $name = $arrTown["name"];

            dump("town $name : persist");

        }else {
            $town = $this->em->getRepository(Town::class)->find($param->getAppId());
            $town->setName($arrTown["name"]);
            //dd($town);

        }
        $this->em->flush();

    }
    /**
     * 
     */
    public function createSector(array $arrSector, Territorry $territorry)
    {
        $iriSector = "/sectors/". $arrSector["id"];
        //dd($arrSector);
        $param = $this->getParam($iriSector);

        if (is_null($param)) {

            $sector = new Sector;
            $sector->setName($arrSector["name"]);
            $sector->setTerritorry($territorry);

            $this->em->persist($sector);
            
            $this->setParam($iriSector, $sector->getId());
            $name = $arrSector["name"];

            dump("sector $name : persist");

        }else {
            $sector = $this->em->getRepository(Sector::class)->find($param->getAppId());
            $sector->setName($arrSector["name"]);
        }
        $this->em->flush();

    }
}


/**
 * 
 */
class Param {
    
    /**
     * @var string
     */
    private $iri;
    /**
     * @var int
     */
    private $appId;

    /**
     * 
     */
    public function __construct(string $iri, int $appId) {
        $this->iri = $iri;
        $this->appId = $appId;
    }

    /**
     * Get the value of iri
     *
     * @return  string
     */ 
    public function getIri()
    {
        return $this->iri;
    }

    /**
     * Get the value of appId
     *
     * @return  int
     */ 
    public function getAppId()
    {
        return $this->appId;
    }
}