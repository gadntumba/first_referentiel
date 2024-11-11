<?php

namespace App\Command;

use App\Repository\ObservationRepository;
use App\Services\NotificationManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:notification:send-all',
    description: 'Add a short description for your command',
)]
class NotificationSendAllCommand extends Command
{
    public function __construct(
        private NotificationManager $manager,
        private ObservationRepository $repository
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
        $all = $this->repository->findByNotAsk();

        foreach ($all as $key => $obs) {
            try {
                $this->manager->notify($obs);
                $io->success("send obs : ". $obs->getId() ." ". $obs->getTitle());
                
            } catch (\Throwable $th) {
                $io->error("Erreur for obs : ". $obs->getId() ." ". $obs->getTitle());
            }
            
        }
        $io->info("finish ");

        return Command::SUCCESS;
    }
}
