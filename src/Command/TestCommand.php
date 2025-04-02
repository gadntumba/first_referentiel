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

#[AsCommand(
    name: 'app:test',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    public function __construct(private ContainerInterface $container) {
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
        /*$arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }*/
        $dirName = $this->container->getParameter("kernel.project_dir") ."/data";
        $fileName = $dirName . "/productor.json";
        $io->success($fileName);
        $data = json_decode(file_get_contents($fileName), true);
        $base64String = $data["personnalIdentityData"]["photo"];
         // 1. Décoder la chaîne base64
        $decodedData = base64_decode($base64String);
        $extension= "png";
        $uploadDirectory=$dirName;
        
        if ($decodedData === false) {
            throw new \InvalidArgumentException('Invalid base64 string');
        }
        
        // 2. Vérifier que les données décodées sont bien une image
        if (@imagecreatefromstring($decodedData) === false) {
            throw new \InvalidArgumentException('The decoded string is not a valid image');
        }
        
        // 3. Créer un nom de fichier unique
        $fileName = uniqid().'.'.$extension;
        $filePath = $uploadDirectory.'/'.$fileName;
        
        // 4. Sauvegarder le fichier
        file_put_contents($filePath, $decodedData);
        #dump($data["personnalIdentityData"]["photo"]);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
