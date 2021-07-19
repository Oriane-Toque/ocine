<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

				$genres = [
					1 => [
						"name" => "Action",
					],
					2 => [
						"name" => "Comedy",
					],
					3 => [
						"name" => "Drama",
					],
					4 => [
						"name" => "Fantasy",
					],
					5 => [
						"name" => "Horror",
					],
					6 => [
						"name" => "Mystery",
					],
					7 => [
						"name" => "Romance",
					],
					8 => [
						"name" => "Thriller",
					],
					9 => [
						"name" => "Western",
					],
					10 => [
						"name" => "Psychological",
					],
					11 => [
						"name" => "Crime",
					],
					12 => [
						"name" => "Apocalypse",
					],
				];

				foreach($genres as $key => $genreData) {

					$genre = new Genre();
					$genre->setName($genreData['name']);
					$manager->persist($genre);
					// on enregistre le genre dans une référence
					$this->addReference("genre_".$key, $genre);
				}

        $manager->flush();
    }
}
