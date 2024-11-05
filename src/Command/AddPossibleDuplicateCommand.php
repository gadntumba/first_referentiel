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
use Symfony\Component\DependencyInjection\ContainerInterface;
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
        private ContainerInterface $container,
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
        #matching_name_host
        $hostname = $this->container->getParameter("matching_name_host");
        $url = $hostname . "/" . "/get_duplicates";

        foreach ($entities as $i => $entity) {
            $resp = $this->httpClient->request(
                "POST", 
                $url,
                [
                    "json" => ["id"=> $entity->getId()]
                ]
            );
            $data = $resp->toArray();
            #dd($data);
            $key = "correspondances";
            $correspondances = $data[$key];
            $duplicates = [];
            foreach ($correspondances as $k => $correspondance) {
                if ($correspondance["id"] != $entity->getId()) {
                    #$duplicate = $correspondance;
                    array_push($duplicates, $correspondance);
                }
            }
            
            for ($l=0; $l < 2; $l++) { 
                
                $duplicate = $duplicates[$l];
                $entitySec = $this->productorPreloadRepository->find($duplicate["id"]);
    
                $possibities = $this->productorPreloadDuplicateRepository->findDuplicatePossible($entity, $entitySec);

                if (2 > count($possibities)) {
                    $duplicateEntity = new ProductorPreloadDuplicate();
                    $duplicateEntity->setMain($entity);
                    $duplicateEntity->setSecondary($entitySec);
                    $duplicateEntity->setSimilarity((float) $duplicate["score"]);
                    //score
    
                    $this->em->persist($duplicateEntity);
                    $this->em->flush(); 
                    #break;               
                }else{
                    #dump($possibities);
                    #break;
                    
                }
                
            }
            #$possibities = $this->productorPreloadDuplicateRepository->findAll();
            
            #dd($possibities);

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
