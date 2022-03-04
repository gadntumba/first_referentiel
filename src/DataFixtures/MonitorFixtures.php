<?php

namespace App\DataFixtures;
use App\Entity\Monitor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class MonitorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       

        $faker = Factory::create('fr_FR');
                $monitor = new Monitor();
                $monitor->setName($faker->name());
                $monitor->setPhoneNumber($faker->randomNumber());
                $manager->persist($monitor);
                $manager->flush();
        
    }

    
}