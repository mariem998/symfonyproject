<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Job extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Data scientist",
            "Statisticien",
            "Analyste cyber-sécurité",
            "Médecin ORL",
            "Échographiste",
            "Mathématicien",
            "Ingénieur logiciel",
            "Analyste informatique",
            "Pathologiste du discours / langage",
            "Actuaire",
            "Ergothérapeute",
            "Directeur des Ressources Humaines",
            "Hygiéniste dentaire "
        ];
        for ($i=0; $i<count($data);$i++) {
            $job = new \App\Entity\Job();
            $job->setDesignation($data[$i]);
            $manager->persist($job);
        }
        $manager->flush();

    }
}
