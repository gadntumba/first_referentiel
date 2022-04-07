<?php

namespace App\Command;

use App\Entity\Monitor;
use App\Entity\User;
use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\OT;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Faker\Factory;

#[AsCommand(name: 'fake:create-monitor', description: 'Add a short description for your command')]
class FakeCreateMonitorCommand extends Command
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
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('phone_number', null, InputOption::VALUE_REQUIRED, 'monitor phone number')
            ->addOption('iri_ot', null, InputOption::VALUE_REQUIRED, 'ot iri ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $phoneNumber = $input->getOption('phone_number');
        $iri = $input->getOption('iri_ot');


        if (!$phoneNumber) {
            throw new \Exception("option '--phoneNumber' required ");

        }
        if (!$iri) {
            throw new \Exception("option '--iri' required ");
        }

        $faker = Factory::create('fr_FR');
        $em = $this->em;
        $iriConverter = $this->iriConverter;

        $userMonitor = new User;
        $userMonitor->setName($faker->name());
        $userMonitor->setFirstName($faker->firstName());
        $userMonitor->setLastName($faker->lastName());
        $userMonitor->setSexe($faker->randomElement(['M','F']));
        $userMonitor->setPhoneNumber($phoneNumber);
        $userMonitor->setEmail($faker->email());

        $conn = $em->getConnection();
        $sql = "SELECT Max(u.id) max_id FROM user u";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([]);
        
        $maxId = $resultSet->fetchAllAssociative()[0]["max_id"];

        $id = is_null($maxId)? 1 :  $maxId+1;

        $userMonitor->setId($id);
        
        /**
         * @var OT
         */
        $ot = $iriConverter->getItemFromIri($iri);
        //dd($ot);
        $em->persist($userMonitor);

        $monitor = new Monitor();
        $monitor->setName($faker->name());
        $monitor->setPhoneNumber($faker->randomNumber());

        $monitor->setUser($userMonitor);
        $ot->addMonitor($monitor);

        $em->persist($monitor);

        $em->flush();
        $io->success('Created.');

        return Command::SUCCESS;
    }
}
