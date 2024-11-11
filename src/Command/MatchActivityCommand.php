<?php

namespace App\Command;

use App\Entity\EntrepreneurialActivity;
use App\Entity\Organization;
use App\Entity\Productor;
use App\Repository\OrganizationRepository;
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
    name: 'app:match-activity',
    description: 'Add a short description for your command',
)]
class MatchActivityCommand extends Command
{
    public function __construct(
        private ProductorRepository $productorRepository,
        private OrganizationRepository $organizationRepository,
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
        $data = $this->productorRepository->findBy(["isNormal" => true]);
        /*$count = count($data);
        $activities = $this->productorRepository->countByAcitivities();

        foreach ($activities as $key => $activity) {
            $activities[$key]["percentage"] =  ($activities[$key]["total"]/$count)*100;
        }
        dd($activities);*/
        //$arg1 = $input->getArgument('arg1');
        $groups = [];

        foreach ($data as $key => $productor) 
        {
            $productor->getId();

           /* if (!is_null($productor->getActivityType()) && !empty($productor->getActivityType())) 
            {

                $io->info("Already : ". $productor->getName());
                continue;
            }*/
            if (!is_null($productor->getActivitiesType()) && !empty($productor->getActivitiesType())) 
            {

                $io->info("Already : ". $productor->getName());
                continue;
            }
               
            //dd($productor->getActivityType());

            /**
             * @var EntrepreneurialActivity
             */
            $activity = $productor->getEntrepreneurialActivities()->last();

            if(is_null($activity)) {
                //$io->info("Ok : ". $productor->getId());
                continue;
            }

            $activities = $activity->getActivities();

            if (is_null($activities)) {
                continue;
            }

            $sectorAgroForestry = isset($activities["5"])?$activities["5"]:null;
            $sectorIndustry = isset($activities["6"])?$activities["6"]:null;
            $sectorServices = isset($activities["7"])?$activities["7"]:null;
            $sectorGreeEconomy = isset($activities["8"])?$activities["8"]:null;
            $otherActivitySector = isset($activities["9"])?$activities["9"]:null;

            //dd(empty($sectorAgroForestry));
            $activitiesType = [];
            if (
                is_null($sectorAgroForestry) && 
                is_null($sectorIndustry) && 
                is_null($sectorServices) && 
                is_null($sectorGreeEconomy) && 
                is_null($otherActivitySector) 
            ) 
            {
               continue; 
            }
            
            if (
                !is_null($sectorAgroForestry) && !empty($sectorAgroForestry)
            ) 
            {
                array_push($activitiesType, Productor::ACTIVITY_SECTOR_AGROFORESTRY);

               // $productor->setActivityType(Productor::ACTIVITY_SECTOR_AGROFORESTRY);

            }if (
                !is_null($sectorIndustry)  && !empty($sectorIndustry)
            ) 
            {
                array_push($activitiesType, Productor::ACTIVITY_SECTOR_INDUSTRY);


                //$productor->setActivityType(Productor::ACTIVITY_SECTOR_INDUSTRY);

            }if (
                !is_null($sectorServices) && !empty($sectorServices)
            ) 
            {
                array_push($activitiesType, Productor::ACTIVITY_SECTOR_SERVICES);
                //$productor->setActivityType(Productor::ACTIVITY_SECTOR_SERVICES); 
            } if (
                !is_null($sectorGreeEconomy) && !empty($sectorGreeEconomy)
            ) 
            {
                array_push($activitiesType, Productor::ACTIVITY_SECTOR_GREE_ECONOMY);
                //$productor->setActivityType(Productor::ACTIVITY_SECTOR_GREE_ECONOMY);
            }
            if (
                !is_null($otherActivitySector) && !empty($otherActivitySector)
            ) 
            {
                array_push($activitiesType, Productor::ACTIVITY_SECTOR_OTHER);
                //$productor->setActivityType(Productor::ACTIVITY_SECTOR_OTHER);
            }
            //$groups[$key]["count"] = $groups[$key]["count"] + 1;
            $productor->setActivitiesType($activitiesType);
            
            $io->info("Ok : ". $productor->getName());

        }

        //dd(count($groups));
        $io->info("Nbre Activities : OK ". count($data));


        $this->em->flush();

        $io->success('');

        return Command::SUCCESS;
    }
    private function fctRetirerAccents($varMaChaine)
    {
        $search  = array('c/ ',' ','À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        //Préférez str_replace à strtr car strtr travaille directement sur les octets, ce qui pose problème en UTF-8
        $replace = array('','','A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
    
        $varMaChaine = str_replace($search, $replace, $varMaChaine);
        return $varMaChaine; //On retourne le résultat
    }
}
