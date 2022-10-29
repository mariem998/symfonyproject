<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EditorsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create('fr_FR');
        for($i=0; $i<100; $i++){
            $editors = new Editor();
            $editors->setFirstname($faker->firstName);
            $editors->setEmail($faker->email);
            $manager->persist($editors);
        }

        $manager->flush();
    }
}
