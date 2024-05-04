<?php

namespace App\Command;

use App\Entity\EntrepreneurialActivity;
use App\Entity\Observation;
use App\Entity\Productor;
use App\Repository\DataBrutRepository;
use App\Repository\ObservationRepository;
use App\Repository\OrganizationRepository;
use App\Repository\ProductorRepository;
use App\Repository\TownRepository;
use App\Services\ManagerGetInstigator;
use App\Services\ManagerLoadSubscriber;
use App\Services\ManagerMakeValidateFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use \PhpOffice\PhpSpreadsheet\RichText\RichText;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use  PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\Row;



#[AsCommand(
    name: 'app:test',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    public function __construct(
        private ContainerBagInterface $containerBag,
        private ManagerLoadSubscriber $managerLoadSubscriber,
        private EntityManagerInterface $em,
        private InsertAgromwindaPlacesCommand $managerMatcherLocation,
        private ProductorRepository $productorRepository,
        private TownRepository $townRepository,
        private ManagerGetInstigator $managerGetInstigator,
        private DataBrutRepository $dataBrutRepository,
        private OrganizationRepository $organizationRepository,
        private ManagerMakeValidateFile $managerMakeValidateFile
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dir = $this->containerBag->get("kernel.project_dir")."/var";
        $reader = new Xlsx();
        $path = $dir."/excelFile/agrodata_structure.xlsx";
        $spreadsheet = $reader->load($path);
        //var_dump("ok");
        //die();
        //$spreadsheet->addSheet();
        $sheets = $spreadsheet->getAllSheets();
        $w = new Worksheet();
        //$w->setC;
        $sheet = $sheets[0];
        $countRow = $sheet->getHighestRow();

        $cityData = [];

        for ($i=2; $i < $countRow; $i++) { 
            $el = [];
           // $el["code"] = trim($sheet->getCell("A".$i)->getValue());
           //var_dump($sheet->getCell("B".$i)->getValue());
           //die();
            $el["row"] = $i;
            $el["id"] = trim($sheet->getCell("A".$i)->getValue());
            $el["name"] = trim($sheet->getCell("B".$i)->getValue());
            $el["cityName"] = trim($sheet->getCell("C".$i)->getValue());

            array_push($cityData, $el);
            
        }

        for ($key=962; $key < count($cityData); $key++) {
            $item = $cityData[$key];
            $entity = $this->organizationRepository->find($item["id"]);

            if (is_null($entity)) {
                continue;
            }
            //962

            dump($entity?->getName());
            dump($entity?->getMyHash());
            dump($item["name"]);
            dump($key);

            $entity?->setName($item["name"]);

            if (($key+1) % 10 == 0) {
                $this->em->flush();
                //dd("ok");
            }
        }

        dd("OK");

        dd($cityData[0]);
        //organizationRepository
        $groups = $this->dataBrutRepository->findBy([]);
        dd($groups);
        ///home/nkusu/Téléchargements/agrodata_structure.xlsx
        //$groups = $this->dataBrutRepository->findByGroups();
        //$cities = $this->dataBrutRepository->findByCities();
        //findByCities
        $count = $this->dataBrutRepository->count([]);

        dd($cities);
        //dd($count);

        $data = $this->productorRepository->findAll();
        $groups = [];

        foreach ($data as $key => $productor) 
        {
            
            /**
             * @var EntrepreneurialActivity
             */
            $activity = $productor->getEntrepreneurialActivities()->last();

            if(is_null($activity)) {
                //$io->info("Ok : ". $productor->getId());
                continue;
            }

            $activities = $activity->getActivities();

            if (is_null($activities)) {
                continue;
            }

            if (!isset($activities["15"])) {
               continue; 
            }
            $key = strtolower($this->fctRetirerAccents($activities["15"]));
            //$groups[$key];

            if (!isset($groups[$key])) {

                $groups[$key] = [
                    "name" => $activities["15"],
                    "count" => 0,
                ];

               //continue; 
            }
            $groups[$key]["count"] = $groups[$key]["count"] + 1;
            #$io->info("Ok : ". $activities["15"]);

        }

        dd(count($groups));
        
        $this->managerMakeValidateFile->makeFile("bukavu");

        return Command::SUCCESS;

        
        $cityData = $this->managerMakeValidateFile->getCityData("https://storage.cloud.google.com/agromwinda_platform/bukavu-consolide-65eb86887ed53.xlsx");
        $assets = $this->managerMakeValidateFile->getNotValidatedData();
        $res = $this->managerMakeValidateFile->getApproximativeData($cityData, $assets);
        $res = $this->managerMakeValidateFile->getCerteData($cityData, $assets);

        dd($res);

        /**
         * @var ProductorRepository
         */
        $productorRepository = $this->em->getRepository(Productor::class);
        //findByNotValidated
        $data = $productorRepository->findByNotValidated();

        $assets = array_map(
            function (Productor $item) {
                $el = [];
                $el["name"] = $item->getName();
                $el["lastname"] = $item->getLastName();
                $el["firstname"] = $item->getFirstName();
                $el["town"] = $item->getHousekeeping()?->getAddress()?->getTown()?->getName();
                $el["city"] = $item->getHousekeeping()?->getAddress()?->getTown()?->getCity()?->getName();
                $el["validation"] = "non valide";
                return $el;                
            },
            $data
        );


        //count()

        $path = $this->managerMakeValidateFile->downloadDataAndSave("https://storage.cloud.google.com/agromwinda_platform/bukavu-consolide-65eb86887ed53.xlsx");
        
        $reader = new Xlsx();
        $spreadsheet = $reader->load($path);
        //var_dump("ok");
        //die();
        //$spreadsheet->addSheet();
        $sheets = $spreadsheet->getAllSheets();
        $w = new Worksheet();
        //$w->setC;
        $sheet = $sheets[0];
        $countRow = $sheet->getHighestRow();

        $cityData = [];

        for ($i=2; $i < $countRow; $i++) { 
            $el = [];
           // $el["code"] = trim($sheet->getCell("A".$i)->getValue());
           //var_dump($sheet->getCell("B".$i)->getValue());
           //die();
            $el["row"] = $i;
            $el["name"] = trim($sheet->getCell("A".$i)->getValue());
            $el["lastname"] = trim($sheet->getCell("B".$i)->getValue());
            $el["firstname"] = trim($sheet->getCell("C".$i)->getValue());
            $el["phone1"] = trim($sheet->getCell("D".$i)->getValue());
            $el["phone2"] = trim($sheet->getCell("E".$i)->getValue());
            $el["phone3"] = trim($sheet->getCell("F".$i)->getValue());

            array_push($cityData, $el);
            
        }

        //dd($cityData[0]);
        $res = $this->managerMakeValidateFile->getApproximativeData($cityData, $assets);
        //$res = $this->managerMakeValidateFile->getCerteData($cityData, $assets);

        dd($res);
        

        
        /**
         * @var ObservationRepository
         */
        $obsRepository = $this->em->getRepository(Observation::class);

        $data = $obsRepository->findByNotAsk();

        dd($data);
        
        $this->managerGetInstigator->getIfNotExist();
        return Command::SUCCESS;

        $town = $this->townRepository->findOneBy([]);
        $townId = $town->getId();
        //dd($townId);
        $res = $this->managerMatcherLocation->getParamByAppId("/towns", $townId);

        dd("/api".$res->getIri());
        
        //$produtor = $this->em->getRepository(Productor::class)->find(11);
        //$this->managerLoadSubscriber->load($produtor);
        $this->managerLoadSubscriber->sendEventLoadIfNot();

        return Command::SUCCESS;
        $publicKey = <<<EOD
        -----BEGIN PUBLIC KEY-----
        MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA00RHidTJdhlsynMnbFzg
        t+c7MRcQ0/nipgBN1/o/oJOVhXlUnY2rmCKHyqFUHK6J1QGl0kn4gnJyVpnKyg8B
        U5c/K3EL3lSrp4l8TP2YbWFvfqOrJmPf+DR8AiO+tPkuTkaZFtOWMRZQJNIubkv4
        bFYF1uVafCT2bnDrmPFKhhXhI6r1q32EnJoD58kkOwo9NDlFw5Bau1VBae5TYr7s
        aX+9pz8z4xtF52ih1vjS/7TmiCtoUXTDwU9BWLwrglB2Jf7n37kblKcRQUc6F5fI
        GH8WhSWxb1SflwHpBQrTGd766siSbDQGGuapp38sZRsVilcNbaedfgTKxZoxjF4s
        Oz3I3hMbPPU3zkq8lL1Lq8hta4NDznmJk04e48EVBt+z7PHw9nIh1VKuxz7LGpfx
        sidw6/+6vePPQFzlmDDvvofhYET48392OGBPE/rWh6cZQQCV9R6L5PtbVg0feCzX
        tktQ9EnLJFn0N7oIQrtpPq1O5Lsi8y8s8+GH4dOuzHh/ATDQtdadr3lmJefasMU8
        uwagRKSid1dYZIamlynBkD2m+kXvP1ahvmWaX8e45hVbwfbzSE8X1vNFgHOXvlUH
        UrL7krPlrjH+gpMi1Ftagt65TABKYBh/hI4IhmAJjHc1f8cDO+ymfKkCkG/u+Rbt
        9NJHuP2ScB+4QnyNTcEuh9UCAwEAAQ==
        -----END PUBLIC KEY-----
        EOD;
        $publicKey= file_get_contents($this->containerBag->get("public_key_file_path"));
        //dd($publicKey);
        //public_key_file_path

        $jwt ="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5YWM5ZGJhNy05NTM1LTQzMjYtOWZmYy04OTA5YzFhMDUwMzMiLCJqdGkiOiJmMjZjODZiN2U2OTcxYmFhMTRkNTE3MDIyNjY3MDk0NjExYjlkNTEzMzIxZGZhODUzZDMwNTIxMDMyYTZkYWQ1MmJlOWQyNGIzMDkzNDI1NiIsImlhdCI6MTcwMTk2MjQ0OC4xOTU0MTgsIm5iZiI6MTcwMTk2MjQ0OC4xOTU0MTksImV4cCI6MTczMzU4NDg0OC4xODg3MjYsInN1YiI6IjEiLCJzY29wZXMiOltdLCJwaG9uZU51bWJlciI6IjAwMDAwMDAwMTAiLCJyb2xlcyI6WyJST0xFX1ZPVUNIRVJfU1VQUExJRVJfTUFOQUdFUiIsIlJPTEVfVVNFUiJdfQ.MlGdh2Qe0aIObwQGXaJdw_FzRTutvwYliHvtvvaDtp011xdxMO742D80LA1aubrY2pXUuqkrWxOLaYk3Z4Utf5b9YIRAlT0iii4T7eXxE4agPp9IROQDEwmtAztnWPrU23_h0TQj3R4bMRTYkRnaWB0m2ZVFBk9MB211rsyfxBILTvEdHzfoRoBFAoqgMw-s_-xIBkW4Kac0tqANC3ofHwMxpo7ZsGt2qDNKosfhaiJ-TrGEhJux0pzD-N7OoH2PdO9AOowXLmLsDpPOyFoq0dpzrTcZHWMuwFoKAtoe-DbOD4vCMr7tx3qiFDkcs5fMyRljA_fyh7aFIwEq1ZcptYv8or_DzFE_mlrRRCEUk9BvMXq0lOE4coKgQkwSdvVKgLNqVNym1F5BDajWtu-Ko4BIAUYw2d4ilbUbBekHtLmQfJewhSrclRGzPpPIXdFILEh_RYBMNlzs9tvGXGerQjsAUihQSoYS6h10g306rMxBb4ojjuuVqV02cDAmnfOo5adAHnvtVqnW6oaecM4smmnL6qrBh3KFr0QibZlA3mk094KVg4w4R4xFSAL1H_DaWfD-xHx7hbxhLvgl3_7q_b1OZZdnF3_MXeag77PmBDyUQn4nziI83_Atpc6A5AtatMVFiTsIFki2KWRQgvIrW0k6Mqq9E85oZ-FVcjiy5_c";
        $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
        dd($decoded);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }


    private function fctRetirerAccents($varMaChaine)
    {
        $search  = array('c/ ',' ','À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        //Préférez str_replace à strtr car strtr travaille directement sur les octets, ce qui pose problème en UTF-8
        $replace = array('','-','A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');

        $varMaChaine = str_replace($search, $replace, $varMaChaine);
        return $varMaChaine; //On retourne le résultat
    }
}
