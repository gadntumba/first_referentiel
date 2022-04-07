<?php

namespace App\Command;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Monitor;
use App\Entity\OT;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Faker\Factory;

#[AsCommand(name: 'fake:create-ot', description: 'Add a short description for your command')]
class FakeCreateOtCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var IriConverterInterface
     */
    private $iriConverter;

    /**
     * 
     */
    public function __construct(
        EntityManagerInterface $em,
        IriConverterInterface $iriConverter
    ) {
        $this->em = $em;
        $this->iriConverter = $iriConverter;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description') goalRecordings
            ->addOption('entitled', null, InputOption::VALUE_REQUIRED, 'name of ot')
            ->addOption('goal_recordings', null, InputOption::VALUE_REQUIRED, 'goal recording of ot')
            ->addOption('user_phone_number', null, InputOption::VALUE_REQUIRED, 'phone number of coordonator')
            //->addOption('monitors_phone_number', '',InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'monitors phone number of coordonator',[])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $entitled = $input->getOption('entitled');
        $userPhone = $input->getOption('user_phone_number');
        $goal = $input->getOption('goal_recordings');
        //
        if (!$entitled) {
            throw new \Exception("option '--entitled' required ");

        }
        if (!$userPhone) {
            throw new \Exception("option '--userPhone' required ");
        }
        if (!$goal) {
            throw new \Exception("option '--goal' required ");
        }

        $faker = Factory::create('fr_FR');
        $cordo = new User;
        $em = $this->em;

        $cordo->setName($faker->name());
        $cordo->setFirstName($faker->firstName());
        $cordo->setLastName($faker->lastName());
        $cordo->setSexe($faker->randomElement(['M','F']));
        $cordo->setPhoneNumber($userPhone);
        $cordo->setEmail($faker->email());
        $conn = $em->getConnection();

        $sql = "SELECT Max(u.id) max_id FROM user u";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([]);
        
        $maxId = $resultSet->fetchAllAssociative()[0]["max_id"];

        $id = is_null($maxId)? 1 :  $maxId+1;

        $cordo->setId($id);


        $em->persist($cordo);

        $ot = new OT;
        
        $ot->setName($faker->name());
        $ot->setEntitled($entitled);
        $ot->setEmail($faker->email());
        $ot->setPhoneNumber($faker->randomNumber());
        $ot->setRccm($faker->bothify());
        $ot->setGoalRecordings($goal);
        
        $ot->addUser($cordo);

        $em->persist($ot);


        //$manager->flush();

        /*foreach ($monitors as $key => $monitorNumber) {

            $userMonitor = new User;
            $userMonitor->setName($faker->name());
            $userMonitor->setFirstName($faker->firstName());
            $userMonitor->setLastName($faker->lastName());
            $userMonitor->setSexe($faker->randomElement(['M','F']));
            $userMonitor->setPhoneNumber($monitorNumber);
            $userMonitor->setEmail($faker->email());

            $em->persist($userMonitor);

            $monitor = new Monitor();
            $monitor->setName($faker->name());
            $monitor->setPhoneNumber($faker->randomNumber());

            $em->persist($monitor);
        }*/


        //dump($ot);
        //dd($cordo);

        //$iri = $this->iriConverter->getIriFromResourceClass(OT::class);

        //dd($iri);
        $em->flush();


        $io->success('ot created');

        return Command::SUCCESS;
    }
}
