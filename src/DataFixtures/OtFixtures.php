<?php

namespace App\DataFixtures;
use App\Entity\Ot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class OtFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       

        $faker = Factory::create('fr_FR');
            
                $ot = new Ot();
                $ot->setName($faker->name());
                $ot->setEntitled($faker->name());
                $ot->setEmail($faker->email());
                $ot->setPhoneNumber($faker->randomNumber());
                $ot->setRccm($faker->bothify());
                $ot->setGoalRecordings($faker->randomNumber());
                $manager->persist($ot);
                $manager->flush();
        
    }

    
}