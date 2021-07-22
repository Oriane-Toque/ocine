<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\MovieDbProvider;
use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

				$faker = Faker\Factory::create("fr_FR");

				$faker->addProvider(new MovieDbProvider);

				for($nbGenre = 1; $nbGenre < 50; $nbGenre++) {

					$genre = new Genre();
					$genre->setName($faker->unique()->movieGenre());
					$manager->persist($genre);
					// on enregistre le genre dans une référence
					$this->addReference("genre_".$nbGenre, $genre);
				}

        $manager->flush();
    }
}
