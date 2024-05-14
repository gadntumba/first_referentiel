<?php

namespace App\Command;

use App\Entity\DownloadItemProductor;
use App\Entity\EntrepreneurialActivity;
use App\Repository\DownloadItemProductorRepository;
use App\Repository\ProductorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-download',
    description: 'Add a short description for your command',
)]
class LoadDownloadCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductorRepository $productorRepository,
        private DownloadItemProductorRepository $downloadItemProductorRepository
    ) 
    {
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
        
        $count_shunk = 1000;
        $i = 0;

        do {
            $offset = ($i*1000)+1;
            
            $data = $this->productorRepository->findBy(["isActive" => true, "isNormal" => true], null, $count_shunk, $offset);

            foreach ($data as $key => $productor) 
            {
                $entity = $this->downloadItemProductorRepository->findOneBy(["productorId" => $productor->getId()]);

                if (!is_null($entity)) {
                    unset($productor);
                    continue;
                }

                $downloadItem = new DownloadItemProductor();
                $downloadItem->setProductorId($productor->getId());
                $downloadItem->setCity($productor->getHousekeeping()?->getAddress()?->getTown()?->getCity());
                $downloadItem->setTown($productor->getHousekeeping()?->getAddress()?->getTown());
                
                /**
                 * @var EntrepreneurialActivity
                 */
                $activity = $productor->getEntrepreneurialActivities()?->last();

                $activityDataBrut = $activity->getActivities()?$activity->getActivities():[];

                /*if (isset($activityDataBrut[0])) {
                    dd($activityDataBrut[0]);
                }*/

                if (!isset($activityDataBrut[0]) || empty($activityDataBrut[0])) {
                    $io->success('KO '.$productor->getId().' - '.$productor->getFirstName().' - '.$productor->getLastName()); 
                    //dump();
                    continue;
                    //dd($activityDataBrut[1]);
                }
                //continue;
                    //continue;

                //dd();

                $jsonData = [
                    "firstName" => $productor->getFirstName(),
                    "lastName" => $productor->getLastName(),
                    "name" => $productor->getName(),
                    "phoneNumber" => $productor->getPhoneNumber(),
                    "adressLine" => $productor->getHousekeeping()?->getAddress()?->getLine(),
                    //"town" => $productor->getHousekeeping()?->getAddress()?->getTown(),
                    //"city" => $productor->getHousekeeping()?->getAddress()?->getTown()?->getCity(),
                    //isNormal
                    "description" => isset($activityDataBrut[0])?$activityDataBrut[0]:null,
                    "sectorAgroTransformation" => isset($activityDataBrut[5])?$activityDataBrut[5]:null,
                    "sectorIndustry" => isset($activityDataBrut[6])?$activityDataBrut[6]:null,
                    "sectorServices" => isset($activityDataBrut[7])?$activityDataBrut[7]:null,
                    "sectorGreeEconomy" => isset($activityDataBrut[8])?$activityDataBrut[8]:null,
                    "affiliationStructure" => isset($activityDataBrut[15])?$activityDataBrut[15]:null,
                    //15
                ];

                $downloadItem->setDataBrut($jsonData);
                $this->em->persist($downloadItem);

                //dd($downloadItem);

                $this->em->flush(); 
                $io->success(''.$productor->getId().' - '.$productor->getFirstName().' - '.$productor->getLastName()); 

                unset($downloadItem);
                unset($activity);
                unset($jsonData);
                unset($productor);
            }
            $i++;

        } while (true);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
