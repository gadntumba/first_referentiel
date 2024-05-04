<?php

namespace App\Command;

use App\Entity\EntrepreneurialActivity;
use App\Entity\Organization;
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
    name: 'app:match-organization',
    description: 'Add a short description for your command',
)]
class MatchOrganizationCommand extends Command
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
        //$arg1 = $input->getArgument('arg1');
        $data = $this->productorRepository->findBy(["isNormal" => true]);
        $groups = [];

        foreach ($data as $key => $productor) 
        {
            $productor->getId();
            $organisation = $productor->getOrganization();

            if (!is_null($organisation) && !is_null($organisation->getCity())) 
            {
                $io->info("Already : ". $productor->getName());
                continue;
            }

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

            if (!isset($activities["15"])) {
               continue; 
            }
            $city = $productor->getHousekeeping()?->getAddress()?->getTown()?->getCity();
            $cityName = $city?->getName()?$city?->getName():"";
            
            $key = strtolower($this->fctRetirerAccents($activities["15"]))."-".strtolower($this->fctRetirerAccents($cityName));

            $group = $this->organizationRepository->findOneBy(["myHash" => $key]);

            if (is_null($group)) {
                $group = new Organization();
                $group->setMyHash($key);
                $city = $productor->getHousekeeping()?->getAddress()?->getTown()?->getCity();
                $group->setCity($city);
                $group->setName($activities["15"]);

                $this->em->persist($group);
                $this->em->flush();

            }
            //$groups[$key];


            $productor->setOrganization($group);
            //$groups[$key]["count"] = $groups[$key]["count"] + 1;
            $io->info("Ok : ". $productor->getName());

        }

        //dd(count($groups));
        $io->info("Nbre groups : ". $this->organizationRepository->count([]));


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
