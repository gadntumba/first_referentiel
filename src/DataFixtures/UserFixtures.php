<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); 
        $user = new User();
                
                $user->setName($faker->name());
                $user->setFirstName($faker->firstName());
                $user->setLastName($faker->lastName());
                $user->setSexe($faker->randomElement(['H','F']));
                $user->setPhoneNumber($faker->randomNumber());
                $user->setEmail($faker->email());
                $manager->persist($user);
                $manager->flush();
            }
            
    
}