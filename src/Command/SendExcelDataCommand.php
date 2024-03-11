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
        //

        //dd($googleServiceUrl);
        $excelFilePath = $projectDir . "/var/excelFile";

        $filesName = [
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
            
        }


        return Command::SUCCESS;
    }
}
