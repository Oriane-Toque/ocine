<?php

namespace App\DataFixtures;

use App\Entity\Casting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CastingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create("fr_FR");

				for($nbCasting = 1; $nbCasting <= 50; $nbCasting++) {

					// récupération des références dont on a besoin
					$person = $this->getReference("person_".$faker->numberBetween(1, 50));
					$movie = $this->getReference("movie_".$faker->numberBetween(1, 100));

					$casting = new Casting;
					$casting->setRole($faker->name());
					$casting->setCreditOrder($faker->numberBetween(1, 15));
					$casting->setMovie($movie);
					$casting->setPerson($person);

					$manager->persist($casting);
				}

        $manager->flush();
    }

		// cette méthode doit retourner la liste des dépendances de ma fixture
		public function getDependencies()
		{
			return [
				// je liste les dépendances de ma fixture
				PersonFixtures::class,
				MovieFixtures::class,
			];
		}
}
