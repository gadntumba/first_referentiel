<?php

namespace App\Command;

use App\Entity\Productor;
use App\Repository\ProductorRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:change-dist',
    description: 'Add a short description for your command',
)]
class ChangeDistCommand extends Command
{
   const ME_PERIOD = 30*24*60*60; 
    public function __construct(
        private  ProductorRepository $repository,
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
        $date = new DateTime("2024-02-15");
        $max = 0;
        $m = 0;
        for ($i=0; $i < 89; $i++) { 
            $date = (new DateTime($date->format("Y-m-d")))->modify('+1 day');

            $data = $this->repository->findByDay($date);//findByDay
            //dd($data);
            if (count($data) > $max) {
                $max = count($data);
            }

            $period = 30*24*60*60;

            $matchData = array_reduce(
                $data,
                function (array $prev, Productor $curr) : array 
                {
                    $city = $curr->getHousekeeping()?->getAddress()?->getTown()?->getCity();
                    if (!isset($prev[$city->getId()])) {
                        $prev[$city->getId()] = [];
                    }
                    if (!is_null($city)) {
                        //$prev[$city->getId()][] = $curr;
                        array_push($prev[$city->getId()], $curr);
                    }
                    return $prev;
                },
                []
            );


            //dd(array_keys($matchData));

            $timestamp = $this->getFirstTimestamp($date);
            //$firstDate = (new DateTime())->setTimestamp($timestamp);
            //dd($firstDate);

            $dataValues = array_values($matchData);

            foreach ($dataValues as $key => $items) 
            {
                dump("Group : ".$key);
                dump(count($items));

                if (count($items) == 0) {
                    continue;
                }
                
                $match = [];

                $points = array_map(
                    function (Productor $productor) use(&$match) : array {
                        //dd($match);
                        $match[$productor->getId()] = $productor;
                        //dd($match);

                        return [
                            'longitude' => $productor->getLongitude(), 'latitude' => $productor->getLatitude(), 'id' => $productor->getId()
                        ];
                    },
                    $items
                );
                
                //dd($match);
        
                // Données de test (remplacez par vos données réelles)
                /*$points = array(
                    array('longitude' => 2.3522, 'latitude' => 48.8566, 'id' => 1), // Paris
                    array('longitude' => -0.1276, 'latitude' => 51.5074, 'id' => 2), // Londres
                    array('longitude' => 13.4050, 'latitude' => 52.5200, 'id' => 3), // Berlin
                    array('longitude' => -74.0060, 'latitude' => 40.7128, 'id' => 4), // New York
                    // Ajoutez d'autres points selon vos besoins
                );*/
        
                // Nombre de clusters souhaité
                //$k = min( count($points), 6);
                $n = count($points);
                // Exécuter l'algorithme K-Means
                if ($n > 30) {
                    $k = round(1+3.22 * log10($n));
                    $groupes = $this->k_means($points, $k);

                }else {
                    $groupes = [$points];
                }
        
                // Afficher les résultats
                foreach ($groupes as $indice => $groupe) 
                {
                    //echo "Groupe $indice:\n";
                    //dump("Group : ".$key.$indice);
                    //dump(count($groupe));
                    $count = count($groupe);
                    
                    //$timestamp = $this->getFirstTimestamp($date);
                    $shunk = self::ME_PERIOD / $count;
                    $persPerDay = ceil($count / 30);
                    dump($persPerDay);
                    //die();
                // dd($count / 30);
                    $dayNormal = 23*3600 + 59*60+ 59;
                    $day = 11*3600 + 59*60+ 59;
                    $busTime = 5*3600 + 59*60+ 59;
                    $j =0;

                    for ($i=0; $i < count($groupe); $i++) 
                    {
                        $point = $groupe[$i];
                        dump($i);
                        $mod = $i % $persPerDay;
                        $chunk = $busTime + round(($day * $mod)/$persPerDay + (random_int(0, round($day*4/5) ))) ;
                        //$createdAt = (new DateTime())->setTimestamp($chunk );
                        //dd($createdAt);
                        $createdAt = (new DateTime())->setTimestamp( $j*$dayNormal + $timestamp + $chunk );
                        //dump($persPerDay-1);
                        /**
                         * @var Productor
                         */
                        $productor = $match[$point["id"]];

                        $productor->setCreatedAtBus($createdAt);

                        dump($productor->getCreatedAtBus());
                        //dump($createdAt);
                        //die();
                        if ($mod == $persPerDay-1) {
                            //dump("");
                            $j++;                               
                        } 
                        $m++;            
                    }

                    $this->em->flush();

                }
                
            }
            
        }

        //$date->modify('+1 day');
        

        //dd($matchData);


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        dump($max);

        return Command::SUCCESS;
    }

    private function getFirstTimestamp(DateTime $date) : int {

        $period = self::ME_PERIOD;
        $firstDateTime = new DateTime("2024-02-15");
        $lastDateTime = new DateTime("2024-05-15");

        $timestampDate = $date->getTimestamp();

        $timestamp1 = $firstDateTime->getTimestamp();
        $timestamp2 = $lastDateTime->getTimestamp();

        $diffTimeStamp = $timestampDate - $period;
        $sumTimeStamp = $timestampDate + $period;

        $resTimeStamp = 0;

        if ($sumTimeStamp > $timestamp2 ) {
            $resTimeStamp = $diffTimeStamp;
        }else {
            $resTimeStamp = $sumTimeStamp;
        }

        return $resTimeStamp;

    }


    // Fonction pour calculer la distance entre deux points (longitude, latitude)
    private function calculer_distance($point1, $point2) {
        $earth_radius = 6371; // Rayon moyen de la Terre en kilomètres
        $delta_lat = deg2rad($point2['latitude'] - $point1['latitude']);
        $delta_lon = deg2rad($point2['longitude'] - $point1['longitude']);
        $a = sin($delta_lat / 2) * sin($delta_lat / 2) + cos(deg2rad($point1['latitude'])) * cos(deg2rad($point2['latitude'])) * sin($delta_lon / 2) * sin($delta_lon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earth_radius * $c;
        return $distance;
    }

    // Fonction pour attribuer chaque point au groupe le plus proche
    private function assigner_groupes($points, $centres) {
        $groupes = array();
        //dd($centres);
        foreach ($points as $point) {
            $distance_min = INF;
            $groupe_assigne = null;
            foreach ($centres as $cle => $centre) {
                $distance = $this->calculer_distance($point, $centre);
                if ($distance < $distance_min) {
                    $distance_min = $distance;
                    $groupe_assigne = $cle;
                }
            }
            $groupes[$groupe_assigne][] = $point;
        }
        return $groupes;
    }


    // Fonction pour recalculer les centres des groupes
    private function recalculer_centres($groupes) {
        $nouveaux_centres = array();
        foreach ($groupes as $groupe) {
            $somme_lat = 0;
            $somme_lon = 0;
            foreach ($groupe as $point) {
                $somme_lat += $point['latitude'];
                $somme_lon += $point['longitude'];
            }
            $nombre_points = count($groupe);
            $nouveau_centre = array('latitude' => $somme_lat / $nombre_points, 'longitude' => $somme_lon / $nombre_points);
            $nouveaux_centres[] = $nouveau_centre;
        }
        return $nouveaux_centres;
    }

    // Fonction pour vérifier la convergence de l'algorithme K-Means
    private function est_converge($centres_precedents, $centres_actuels, $epsilon) {
        foreach ($centres_precedents as $cle => $centre) {
            try {
                $distance = $this->calculer_distance($centre, $centres_actuels[$cle]);
                
            } catch (\Throwable $th) {
                dump(count($centres_actuels));
                dump($centres_precedents);
                //count($centres_precedents);
                throw $th;
            }
            if ($distance > $epsilon) {
                return false;
            }
        }
        return true;
    }
    // Fonction pour exécuter l'algorithme K-Means
    private function k_means($points, $k, $max_iterations = 100, $epsilon = 0.001) {
        // Sélectionner aléatoirement les centres initiaux
        //dd($points);
        $indices_centres = array_rand($points, $k);
        //dump($k);

        $centres = array();
        if (is_integer($indices_centres)) {
            $indices_centres = [$indices_centres];
        }
        foreach ($indices_centres as $indice) {
            $centres[] = $points[$indice];
        }
        
        $iteration = 0;
        do {
            $iteration++;
            $centres_precedents = $centres;
            
            // Assigner chaque point au groupe le plus proche
            $groupes = $this->assigner_groupes($points, $centres);
            
            // Recalculer les centres des groupes
            //dump($groupes);
            $centres = $this->recalculer_centres($groupes);
            
            // Vérifier la convergence
            try {
                if ($this->est_converge($centres_precedents, $centres, $epsilon) || $iteration >= $max_iterations) {
                    break;
                }
            } catch (\Throwable $th) {
                dd(count($points));
                throw $th;
            }
        } while (true); 

        return $groupes;
    }



}
