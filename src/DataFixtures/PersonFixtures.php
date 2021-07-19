<?php

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

				$faker = Faker\Factory::create('fr_FR');

				for($nbPerson = 1; $nbPerson <= 50; $nbPerson++) {

					$person = new Person();
					$person->setFirstname($faker->firstName());
					$person->setLastname($faker->lastName());
					$manager->persist($person);

					// on enregistre la personne dans une référence
					$this->addReference("person_" . $nbPerson, $person);
				}

        $manager->flush();
    }
}
