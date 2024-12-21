<?php

namespace App\Command;

use App\Entity\ProductorPreload;
use App\Repository\ProductorPreloadRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[AsCommand(
    name: 'app:load-preload',
    description: 'Add a short description for your command',
)]
class LoadPreloadCommand extends Command
{
    public function __construct(
        private ProductorPreloadRepository $repository,
        private ContainerInterface $container
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
        $data_arr =[ '"ID";"Nom";"PostNom"; "PréNom";"Structure";"Secteur";"Ville";"Commune Activité";"Quartier Activité";"Adresse Activité";"Date Signale Enregistrement";"Rapport";"commentaire";"Enregistré";'];
        $projectDir = $this->container->getParameter('kernel.project_dir');
        
        $count_shunk = 1000;
        $i = 0;

        do {
            $offset = ($i*1000)+1;
            
            $data = $this->repository->findBy([], ["id"=>"Desc"], $count_shunk, $offset);
            foreach ($data as $key => $item) 
            {
                $itemArray = [
                    strtoupper(substr($item->getCityEntity()?->getName(), 0, 3))."-". $item->getId(),
                    $item->getName(),
                    $item->getLastname(),
                    $item->getFirstname(),
                    $item->getStructure(),
                    $item->getSector(),
                    $item->getCityEntity()?->getName(),
                    $item->getTown(),
                    $item->getQuarter(),
                    $item->getAddress(),
                    $item->getContactAt(),
                    $item->getContactRepport(),
                    $item->getContanctComment(),
                   is_null($item->getProductor())?"OUI":"NON",
                ];

                foreach ($itemArray as $k => $val) {
                    $itemArray[$k] = "\"".str_replace("\"","`",$val)."\"";
                }
                array_push($data_arr, implode(";", $itemArray));
            }
            
            $i++;

        } while (count($data));

        $data_str = implode("\n", $data_arr);

        file_put_contents($projectDir."/download_preload_all.csv", $data_str);



        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
