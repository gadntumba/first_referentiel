<?php

namespace App\Command;

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
    name: 'app:change-housezise',
    description: 'Add a short description for your command',
)]
class ChangeHouseziseCommand extends Command
{
    public function __construct( 
        private ProductorRepository $productorRepository,
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
        $data = $this->productorRepository->findGreatHousesize();
        foreach ($data as $key => $item) {
            dump($item->getHouseholdSize());
            $item->setHouseholdSize(random_int(7, 15));
        }
        $this->em->flush();

        //dd();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
