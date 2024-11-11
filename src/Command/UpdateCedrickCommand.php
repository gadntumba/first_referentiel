<?php

namespace App\Command;

use App\Repository\ProductorRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[AsCommand(
    name: 'app:update-cedrick',
    description: 'Add a short description for your command',
)]
class UpdateCedrickCommand extends Command
{
    public function __construct(
        private ContainerInterface $container, 
        private ProductorRepository $productorRepository, 
        private EntityManagerInterface $em) 
    {
        parent::__construct();        
    }

    protected function configure(): void
    {
        $this
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            ->addOption('first_col', null, InputOption::VALUE_REQUIRED, 'column first')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $firstCol = (int) $input->getOption('first_col');
        //dd($firstCol);
        /*$arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }*/
        if (!$firstCol) {
            throw new \Exception("option '--first_col' required ");

        }

        $pathData = $this->container->getParameter('kernel.project_dir')."/var/excelFile/data3.csv";
        $dataStr = file_get_contents($pathData);

        $dataLines = explode("\n", $dataStr);
        //dd()

        //dd(count($dataLines));
        array_shift($dataLines);
        $count = count($dataLines);

        //$i = 0;

        for ($i=$firstCol-1; $i < $count; $i++) { 
            $line = $dataLines[$i];
        

        //foreach ($dataLines as $key => $line) {
            
            $item = $this->formatItem(explode(";", $line));

            //dump($productor);
            

            $productor = $this->productorRepository->findOneBy(["phoneNumber" => "0".$item[0]]);
            
            if (is_null($productor)) 
            {
                $productor = $this->productorRepository->findOneBy(["phoneNumber" => "00".$item[0]]);
            }

            if (is_null($productor)) 
            {
                $productor = $this->productorRepository->findOneBy(["phoneNumber" => "000".$item[0]]);
            }

            if (is_null($productor)) 
            {
                continue;
            }
            
            if (is_null($productor->getOldCordinates())) {
                $oldCoordinates = [
                    "latitude" => $productor->getLatitude(),
                    "longitude" => $productor->getLongitude(),
                    "altitude" => $productor->getAltitude(),
                ];
                $productor->setOldCordinates($oldCoordinates);
            }
            
            $productor->setLatitude((float) str_replace(",",".",$item[1]));
            $productor->setLongitude((float) str_replace(",",".",$item[2]));
            $productor->setEditAt(new DateTime());
            $productor->setAiActivitySector((string) $item[3]);
            dump($productor->getOldCordinates());
            dump($productor->getLatitude());
            dump($productor->getLongitude());
            dump($productor->getAiActivitySector());
            dump($productor->getEditAt());
            //dd($item[1]);

            //$i++;

            if ($i%25 == 0) {

                dump("Flush modification");

                $this->em->flush();
                
            }
            $perc = ($i/$count)*100;
            dump("percenetage : ". $perc);
            dump("count : ". $i);
            
            //dd($item);
            
        }

        $this->em->flush();


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    function formatItem(array $item) : array 
    {
        $newItem = [];
        foreach ($item as $key => $value) {

            if (!empty($value) && $value[0] == '"') {
                array_push($newItem, substr($value, 1, strlen($value)-2));
            }else {

                array_push($newItem, $value);
            }
        }

        return $newItem;
    }
}
