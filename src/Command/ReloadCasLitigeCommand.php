<?php

namespace App\Command;

use App\Repository\ProductorPreloadRepository;
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
    name: 'app:reload-cas-litige',
    description: 'Add a short description for your command',
)]
class ReloadCasLitigeCommand extends Command
{
    public function __construct(
        private ProductorPreloadRepository $repository,
        private ContainerInterface $container,
        private EntityManagerInterface $em
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
        $projectDir = $this->container->getParameter('kernel.project_dir')."/var/kin_cas_litigie.csv";

        $content = file_get_contents($projectDir);

        $data = explode("\n", $content);

        array_shift($data);

        foreach ($data as $key => $itemStr) {
            $itemData = explode(";", $itemStr);
            $id = substr($itemData[0], 4);
            $item = $this->repository->find($id);
            if (is_null($item)) {
                $io->success('not exist : '. $id);
               continue; 
            }
            $repport = $itemData[12];
            $comment = $itemData[13];
            
            $item->setContactRepport($repport);
            $item->setContanctComment($comment);

            $this->em->flush();
            $io->success('update : '. $item->getId());

        }

        #$preloads = $this->repository->findBy([$i]);


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
