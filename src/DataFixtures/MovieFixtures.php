<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

				for($nbMovie = 1; $nbMovie < 100; $nbMovie++) {

					$movie = new Movie();
					$movie->setTitle($faker->streetName());
					$movie->setCreatedAt(new DateTime());
					$movie->setReleaseDate(new DateTime());
					$movie->setDuration($faker->numberBetween(55, 180));

					$manager->persist($movie);

					// on enregistre le film dans une référence
					$this->addReference("movie_".$nbMovie, $movie);
				}

        $manager->flush();
    }
}
