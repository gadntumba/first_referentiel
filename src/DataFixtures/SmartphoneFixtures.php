<?php

namespace App\DataFixtures;
use App\Entity\Smartphone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class SmartphoneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); 
        $smartphone = new Smartphone();
                
                $smartphone->setMetadata($faker->randomElements(['a', 'b', 'c', 'd', 'e']));
                $smartphone->setImei($faker->bothify());
                $smartphone->setEmailAdress($faker->email());
                $smartphone->setPhoneNumber($faker->randomNumber());
                $smartphone->setPrefixNUI($faker->bothify());
                $smartphone->setCreatedAt($faker->dateTime());
                $smartphone->setLongitude($faker->randomFloat());
                $smartphone->setAltitude($faker->randomFloat());
                $smartphone->setLatitude($faker->randomFloat());
                $manager->persist($smartphone);
                $manager->flush();
            }
            
    
}