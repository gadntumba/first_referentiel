<?php

namespace App\Command;

use App\Entity\ProductorPreload;
use App\Entity\ProductorPreloadDuplicate;
use App\Repository\ProductorPreloadDuplicateRepository;
use App\Repository\ProductorPreloadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:add-possible-duplicate',
    description: 'Add a short description for your command',
)]
class AddPossibleDuplicateCommand extends Command
{
    public function __construct(
        private ProductorPreloadRepository $productorPreloadRepository,
        private ProductorPreloadDuplicateRepository $productorPreloadDuplicateRepository,
        private EntityManagerInterface $em,
        private HttpClientInterface $httpClient
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
        //findWithoutE2Relation
        $entities = $this->productorPreloadRepository->findWithoutE2Relation();
        #$entities = $this->productorPreloadRepository->findAll();
        $allNbr = count($entities);

        #dd($entities);

        foreach ($entities as $i => $entity) {
            $resp = $this->httpClient->request(
                "POST", 
                "http://127.0.0.1:5000/get_duplicates",
                [
                    "json" => ["id"=> $entity->getId()]
                ]
            );
            $data = $resp->toArray();
            #dd($data);
            $key = "correspondances";
            $correspondances = $data[$key];
            $duplicate = null;
            foreach ($correspondances as $k => $correspondance) {
                if ($correspondance["id"] != $entity->getId()) {
                    $duplicate = $correspondance;
                }
            }
            $entitySec = $this->productorPreloadRepository->find($duplicate["id"]);

            $possibities = $this->productorPreloadDuplicateRepository->findDuplicatePossible($entity, $entitySec);
            #$possibities = $this->productorPreloadDuplicateRepository->findAll();
            
            #dd($possibities);

            if (count($possibities) == 0) {
                $duplicateEntity = new ProductorPreloadDuplicate();
                $duplicateEntity->setMain($entity);
                $duplicateEntity->setSecondary($entitySec);

                $this->em->persist($duplicateEntity);
                $this->em->flush(); 
                #break;               
            }else{
                #dump($possibities);
                #break;
                
            }
            //dd($i);
            $io->success('Evolution : ' . (string) ((($i+1)/$allNbr)*100)." %");

        }

        /*$arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');*/

        return Command::SUCCESS;
    }
}
