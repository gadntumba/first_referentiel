<?php

namespace App\Command;

use App\Entity\Productor;
use App\Services\ManagerLoadSubscriber;
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

#[AsCommand(
    name: 'app:subscriber:load',
    description: 'Add a short description for your command',
)]
class LoadSubscriberIfNotCommand extends Command
{
    public function __construct(
        private ContainerBagInterface $containerBag,
        private ManagerLoadSubscriber $managerLoadSubscriber,
        private EntityManagerInterface $em
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
        
        $this->managerLoadSubscriber->sendEventLoadIfNot();

        return Command::SUCCESS;
    }
}
