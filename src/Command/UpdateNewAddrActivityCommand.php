<?php

namespace App\Command;

use App\Entity\EntrepreneurialActivity;
use App\Repository\ProductorRepository;
use App\Repository\TownRepository;
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
    name: 'app:new-addr-activity',
    description: 'Add a short description for your command',
)]
class UpdateNewAddrActivityCommand extends Command
{
    public function __construct(
        private ContainerInterface $container, 
        private ProductorRepository $productorRepository, 
        private TownRepository $townRepository,
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
        $townNames = [
            "kisenso" => 17,
            "ndjili" => 26,
            "lemba" => 18,
            "ngaba" => 27,
            "makala" => 21,
            "bumbu" => 10,
            "selembao" => 31,
            "ngiringiri" => 29,
            "kasavubu" => 13,
            "bandalungwa" => 8,
            "masina" => 23,
            "limete" => 19,
            "ngaliema" => 28,
            "lingwala" => 20,
            "kinshasa" => 15,
            "barumbu" => 9,
            "gombe" => 11,
            "maluku" => 22,
            "montngafula" => 25,
            "kimbanseke" => 14,
            "nsele" => 30,
            "kalamu" => 12,
            "kintambo" => 16,
            "matete" => 24
        ];
        $cityName = "kinshasa";
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

        $pathData = $this->container->getParameter('kernel.project_dir')."/var/excelFile/kinshsasa_with_new_activity.csv";
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
            //dd($item);
            //dump($productor);

            $townKey = isset($item[19])?$item[19]:null;

            if (!$townKey) {
                continue;
            }
            
            $phoneNumber = isset($item[6])? $item[6] : null;
            dump($townKey);
            dump($phoneNumber);
            
            $townID = $townNames[$townKey];
            $addressLine = $item[20];

            $productor = $this->productorRepository->findOneBy(["phoneNumber" => "0".$phoneNumber]);
            
            
            if (is_null($productor)) 
            {
                $productor = $this->productorRepository->findOneBy(["phoneNumber" => "00".$phoneNumber]);
            }

            if (is_null($productor)) 
            {
                $productor = $this->productorRepository->findOneBy(["phoneNumber" => "000".$phoneNumber]);
            }

            if (is_null($productor)) 
            {
                continue;
            }
            $activities = [... $productor->getEntrepreneurialActivities()->toArray()];
            
            /**
             * @var EntrepreneurialActivity
             */
            $activity = array_pop($activities);

            $town = $this->townRepository->find($townID);

            //dd($town);

            if ($town?->getCity()?->getName() != $cityName) {
                dump($i);
                dump("town not conformed");
                dump($phoneNumber);
            }
            
            if (is_null($productor->getOldActivityAddr())) {
                $oldCoordinates = [
                    "townId" => $activity?->getTown()?->getId(),
                    "townName" => $activity?->getTown()?->getName(),
                    "addressLine" => $activity?->getAddressLine()
                ];
                $productor->setOldActivityAddr($oldCoordinates);
            }
            
            /*$productor->setLatitude((float) str_replace(",",".",$item[1]));
            $productor->setLongitude((float) str_replace(",",".",$item[2]));
            $productor->setEditAt(new DateTime());
            $productor->setAiActivitySector((string) $item[3]);*/

            $activity->setAddressLine($addressLine);
            $activity->setTown($town);
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
