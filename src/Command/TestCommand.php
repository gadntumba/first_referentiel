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
    name: 'app:test',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
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
        $produtor = $this->em->getRepository(Productor::class)->find(10);
        $this->managerLoadSubscriber->load($produtor);

        return Command::SUCCESS;
        $publicKey = <<<EOD
        -----BEGIN PUBLIC KEY-----
        MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA00RHidTJdhlsynMnbFzg
        t+c7MRcQ0/nipgBN1/o/oJOVhXlUnY2rmCKHyqFUHK6J1QGl0kn4gnJyVpnKyg8B
        U5c/K3EL3lSrp4l8TP2YbWFvfqOrJmPf+DR8AiO+tPkuTkaZFtOWMRZQJNIubkv4
        bFYF1uVafCT2bnDrmPFKhhXhI6r1q32EnJoD58kkOwo9NDlFw5Bau1VBae5TYr7s
        aX+9pz8z4xtF52ih1vjS/7TmiCtoUXTDwU9BWLwrglB2Jf7n37kblKcRQUc6F5fI
        GH8WhSWxb1SflwHpBQrTGd766siSbDQGGuapp38sZRsVilcNbaedfgTKxZoxjF4s
        Oz3I3hMbPPU3zkq8lL1Lq8hta4NDznmJk04e48EVBt+z7PHw9nIh1VKuxz7LGpfx
        sidw6/+6vePPQFzlmDDvvofhYET48392OGBPE/rWh6cZQQCV9R6L5PtbVg0feCzX
        tktQ9EnLJFn0N7oIQrtpPq1O5Lsi8y8s8+GH4dOuzHh/ATDQtdadr3lmJefasMU8
        uwagRKSid1dYZIamlynBkD2m+kXvP1ahvmWaX8e45hVbwfbzSE8X1vNFgHOXvlUH
        UrL7krPlrjH+gpMi1Ftagt65TABKYBh/hI4IhmAJjHc1f8cDO+ymfKkCkG/u+Rbt
        9NJHuP2ScB+4QnyNTcEuh9UCAwEAAQ==
        -----END PUBLIC KEY-----
        EOD;
        $publicKey= file_get_contents($this->containerBag->get("public_key_file_path"));
        //dd($publicKey);
        //public_key_file_path

        $jwt ="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5YWM5ZGJhNy05NTM1LTQzMjYtOWZmYy04OTA5YzFhMDUwMzMiLCJqdGkiOiJmMjZjODZiN2U2OTcxYmFhMTRkNTE3MDIyNjY3MDk0NjExYjlkNTEzMzIxZGZhODUzZDMwNTIxMDMyYTZkYWQ1MmJlOWQyNGIzMDkzNDI1NiIsImlhdCI6MTcwMTk2MjQ0OC4xOTU0MTgsIm5iZiI6MTcwMTk2MjQ0OC4xOTU0MTksImV4cCI6MTczMzU4NDg0OC4xODg3MjYsInN1YiI6IjEiLCJzY29wZXMiOltdLCJwaG9uZU51bWJlciI6IjAwMDAwMDAwMTAiLCJyb2xlcyI6WyJST0xFX1ZPVUNIRVJfU1VQUExJRVJfTUFOQUdFUiIsIlJPTEVfVVNFUiJdfQ.MlGdh2Qe0aIObwQGXaJdw_FzRTutvwYliHvtvvaDtp011xdxMO742D80LA1aubrY2pXUuqkrWxOLaYk3Z4Utf5b9YIRAlT0iii4T7eXxE4agPp9IROQDEwmtAztnWPrU23_h0TQj3R4bMRTYkRnaWB0m2ZVFBk9MB211rsyfxBILTvEdHzfoRoBFAoqgMw-s_-xIBkW4Kac0tqANC3ofHwMxpo7ZsGt2qDNKosfhaiJ-TrGEhJux0pzD-N7OoH2PdO9AOowXLmLsDpPOyFoq0dpzrTcZHWMuwFoKAtoe-DbOD4vCMr7tx3qiFDkcs5fMyRljA_fyh7aFIwEq1ZcptYv8or_DzFE_mlrRRCEUk9BvMXq0lOE4coKgQkwSdvVKgLNqVNym1F5BDajWtu-Ko4BIAUYw2d4ilbUbBekHtLmQfJewhSrclRGzPpPIXdFILEh_RYBMNlzs9tvGXGerQjsAUihQSoYS6h10g306rMxBb4ojjuuVqV02cDAmnfOo5adAHnvtVqnW6oaecM4smmnL6qrBh3KFr0QibZlA3mk094KVg4w4R4xFSAL1H_DaWfD-xHx7hbxhLvgl3_7q_b1OZZdnF3_MXeag77PmBDyUQn4nziI83_Atpc6A5AtatMVFiTsIFki2KWRQgvIrW0k6Mqq9E85oZ-FVcjiy5_c";
        $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
        dd($decoded);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
