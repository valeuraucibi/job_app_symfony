<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Entity\JobRequirement;
use App\Entity\JobType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	    $faker =  Factory::create('fr_FR');
	    $companies = $manager->getRepository(Company::class)->findAll();
	    $jobTypes = $manager->getRepository(JobType::class)->findAll();

	    // User
	    for ($i=0; $i<30; $i++) {

	    	$user = (new User())
			    ->setLastName($faker->lastName)
			    ->setFirstName($faker->firstName)
			    ->setEmail($faker->email)
			    ->setPassword("password")
			    ->setType($faker->randomElement([1,2]))
			    ->setBirthDate($faker->dateTime)
			    ->setResume("test")
		        ->setCreatedAt($faker->dateTime('now'));

	    	// set company
	    	if ($user->getType() == 2) {

			    // Company
			    $company = (new Company())
				    ->setName($faker->company)
				    ->setDescription($faker->paragraph(10))
				    ->setEmployerNumber($faker->randomNumber())
				    ->setLogo($faker->imageUrl($width = 640, $height = 480))
				    ->setTurnover($faker->randomFloat())
				    ->setCreatedAt($faker->dateTime)
			        ->setActivityArea($faker->domainName);

			    $manager->persist($company);

	    		$user->setCompany($company);

	    		// Job Offer
	    		for ($j=0; $j<30; $j++) {

	    			$jobOffer = (new JobOffer())
					    ->setCompany($user->getCompany())
					    ->setDescription($faker->paragraph(20))
					    ->setTitle($faker->jobTitle)
				        ->setJobType($faker->randomElement($jobTypes))
				        ->setCity($faker->city)
				        ->setSalary($faker->randomFloat())
				        ->setReference($faker->swiftBicNumber)
				        ->setDateStart($faker->dateTime)
				        ->setCreatedAt($faker->dateTime('now'));

	    			// Job requirement
				    for ($k=0; $k<7; $k++) {

				    	$jobRequirement = (new JobRequirement())
						    ->setContent($faker->text)
						    ->setJobOffer($jobOffer)
						    ->setCreatedAt($faker->dateTime('now'));

				    	$manager->persist($jobRequirement);

				    }

				    $manager->persist($jobOffer);

			    }

		    }

	    	$manager->persist($user);

	    }

        $manager->flush();
    }


	public function getDependencies(): array {
		return [
			JobTypeFixtures::class
		];
	}
}
