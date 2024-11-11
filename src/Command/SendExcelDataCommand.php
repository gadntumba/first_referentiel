<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Google\Cloud\Storage\StorageClient;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsCommand(
    name: 'app:send-excel-data',
    description: 'Add a short description for your command',
)]
class SendExcelDataCommand extends Command
{
    /**
     * @var StorageClient
     */
    private $googleStorage;

    public function __construct(private ContainerInterface $container, private SluggerInterface $slugger) {
        parent::__construct();
        $googleServiceUrl = $this->container->getParameter('google_service_url');
        $this->googleStorage = new StorageClient(['keyFilePath' => $googleServiceUrl]);
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
        $projectDir = $this->container->getParameter('kernel.project_dir');
        $googleServiceUrl = $this->container->getParameter('google_service_url');
        //data-received-by-ucp
        $dirNameSource =  'data-received-by-ucp';
        $fileNameDest = 'dest_file.json';

        //dd($googleServiceUrl);
        $excelFilePath = $projectDir . "/var/excelFile";
        $dirPathSource = $excelFilePath."/".$dirNameSource;
        $pathFileNameDest = $excelFilePath."/".'dest_file.json';

        //dd($pathFileNameDest);

        $files = scandir($dirPathSource);
        $result = [];

        //dd($files);
        foreach ($files as $key => $file) {
            if (is_dir($file)) {
                continue;
            }

            $normalFileName = strtolower($this->fctRetirerAccents($file));
            //$fileNameExt = 'bukavu-consolidé.xlsx';
            $arrFileExt = explode(".", $file);
            $arrFileNameNormal = explode(".", $normalFileName);
            $ext = array_pop($arrFileExt);
            $extNormal = array_pop($arrFileNameNormal);
            $originalFilename = implode(".", $arrFileExt);
            //$normalFilename = implode(".", $arrFileNameNormal);

            //dump($originalFilename);
            //dd($ext);        

            $pathName = $dirPathSource."/".$originalFilename.".".$ext;
            //dd($pathName);
            //dd(file_exists($pathName));
            
            
            //$safeFilename = $this->slugger->slug($originalFilename);

            //$fileName = strtolower($safeFilename.'-'.uniqid()) .'.'.$ext;
            $fileName = $normalFileName;
            //normalFileName
            //dump($pathName);
            //dump($fileName);
            //continue;
            $bucket        = $this->googleStorage->bucket('agromwinda_platform');

            $obj = $bucket->upload(
                fopen($pathName,'r'),
                ['name' => $fileName]
            );  

            //$bucket->
            $fullUrl = null;
            if($url = $obj->gcsUri()) {
                //$image->setPath("https://storage.cloud.google.com/" . explode("gs://", $url)[1]);
                $fullUrl = "https://storage.cloud.google.com/" . explode("gs://", $url)[1];
            }
            array_push($result, $fullUrl);
            $io->info($fullUrl);
         

        }

        file_put_contents($pathFileNameDest, json_encode($result));

        //dd();

        /*$filesName = [
            'bukavu.xlsx',
            'bunia.xlsx',
            'goma.xlsx',
            'kananga.xlsx',
            'kin.xlsx',
            'matadi.xlsx',
            'mbujimayi.xlsx'
        ];

        foreach ($filesName as $key => $fileNameExt) 
        {
            //$fileNameExt = 'bukavu-consolidé.xlsx';
            $arrFileExt = explode(".", $fileNameExt);
            $ext = array_pop($arrFileExt);
            $originalFilename = implode(".", $arrFileExt);

            //dump($originalFilename);
            //dd($ext);        

            $pathName = $excelFilePath."/".$originalFilename.".".$ext;
            //dd($pathName);
            //dd(file_exists($pathName));
            
            $safeFilename = $this->slugger->slug($originalFilename);

            $fileName = strtolower($safeFilename.'-'.uniqid()) .'.'.$ext;

            $bucket        = $this->googleStorage->bucket('agromwinda_platform');

            $obj = $bucket->upload(
                fopen($pathName,'r'),
                ['name' => $fileName]
            );  

            //$bucket->
            $fullUrl = null;
            if($url = $obj->gcsUri()) {
                //$image->setPath("https://storage.cloud.google.com/" . explode("gs://", $url)[1]);
                $fullUrl = "https://storage.cloud.google.com/" . explode("gs://", $url)[1];
            }
            $io->info($fullUrl);
            
        }*/


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
