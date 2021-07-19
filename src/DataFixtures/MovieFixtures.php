<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

				for($nbMovie = 1; $nbMovie < 100; $nbMovie++) {

					// récupération des références dont on a besoin
					$genre = $this->getReference("genre_".$faker->numberBetween(1, 12));

					$movie = new Movie();
					$movie->setTitle($faker->streetName());
					$movie->setCreatedAt(new DateTime());
					$movie->setReleaseDate(new DateTime());
					$movie->setDuration($faker->numberBetween(55, 180));

					$movie->addGenre($genre);

					$manager->persist($movie);

					// on enregistre le film dans une référence
					$this->addReference("movie_".$nbMovie, $movie);
				}

        $manager->flush();
    }

		public function getDependencies()
		{
			return [
				GenreFixtures::class,
			];
		}
}
