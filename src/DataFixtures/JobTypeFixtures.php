<?php

namespace App\DataFixtures;

use App\Entity\JobType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class JobTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
	    $faker =  Factory::create('fr_FR');
	    $type = ['Apprentissage', 'CDI', 'CDD', 'Stage', 'Intérim'];

	    for ($i=0; $i<count($type); $i++) {

	    	$jobType = (new JobType())
			    ->setName($type[$i])
			    ->setCreatedAt($faker->dateTime('now'));

	    	$manager->persist($jobType);
	    }

        $manager->flush();
    }
}
