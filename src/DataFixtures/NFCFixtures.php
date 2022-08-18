<?php

namespace App\DataFixtures;
use App\Entity\NFC;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class NFCFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); 
        $nfc = new NFC();
                
                $nfc->setMetadata($faker->randomElements(['a', 'b', 'c', 'd', 'e']));
                $nfc->setLongitude($faker->randomFloat());
                $nfc->setLatitude($faker->randomFloat());
                $nfc->setAltitude($faker->randomFloat());
                $nfc->setCreatedAt($faker->dateTime());
                $manager->persist($nfc);
                $manager->flush();
            }
            
    
}