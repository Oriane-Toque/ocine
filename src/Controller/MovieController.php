<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use DateTime;

class MovieController extends AbstractController
{

	/**
	 * Liste Movies
	 *
	 * @Route("/", name="movie_list")
	 */
	public function list(MovieRepository $movieRepository)
	{

		$movies = $movieRepository->findAllByTitle(
			[],
			[
				"releaseDate" => "DESC",
			]
		);

		dump($movies);

		return $this->render(
			"home/movies.html.twig",
			[
				"title" => "Liste des films",
				"movies" => $movies,
			]
		);
	}

	/**
	 * Read Movie
	 *
	 * @Route("/movie/{id<\d+>}", name="movie_read")
	 */
	public function read(Movie $movie = null, CastingRepository $castingRepository)
	{
		if ($movie === null) {
			throw $this->createNotFoundException("404");
		}

		$castings = $castingRepository->findBy(
			["movie" => $movie],
			["creditOrder" => "ASC"],
		);

		dump($movie);
		dump($castings);

		return $this->render(
			"movie/movie.html.twig",
			[
				"title" => $movie->getTitle(),
				"movie" => $movie,
				"castings" => $castings,
			]
		);
	}

	/**
	 * Test creation entité
	 * 
	 * @Route("/test/create", name="test_create")
	 */
	public function create(): Response
	{
		$movie = new Movie();

		$movie->setTitle("Hulk");
		// date courante si non spécifié
		$movie->setCreatedAt(new DateTime());

		// step 1 : on demande au Manager de *se préparer à* "persister" l'entité
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($movie);

		// step 2 execute les requetes sql nécessaires (insert into, ici)
		$entityManager->flush();

		return new Response('<body>Film ajouté : ' . $movie->getId() . '</body>');
	}

	/**
	 * @Route("/test/edit/{id<\d+>}")
	 */
	public function edit($id)
	{
		// on va chercher le film, on le modifie, on le sauvegarde
		$movieRepository = $this->getDoctrine()->getRepository(Movie::class);
		$movie = $movieRepository->find($id);

		// Mise à jour
		$movie->setTitle("AntMan 2");
		$movie->setUpdatedAt(new DateTime());

		// sauvegarde
		// step 1 - on récupère le Manager de doctrine
		$entityManager = $this->getDoctrine()->getManager();
		// step 2 - exécute les requêtes SQL nécessaires (ici, UPDATE)
		$entityManager->flush();

		return $this->redirectToRoute("test_read", ["id" => $id]);
	}

	/**
	 * @Route("/test/delete/{id<\d+>}")
	 */
	public function delete($id)
	{

		// on va chercher le film dans le repository
		$movieRepository = $this->getDoctrine()->getRepository(Movie::class);
		$movie = $movieRepository->find($id);

		// sauvegarde
		// step 1 - on récupère le Manager de doctrine
		$entityManager = $this->getDoctrine()->getManager();
		// step 2 - suppression du film
		$entityManager->remove($movie);
		// step 3 - exécute les requêtes SQL nécessaires (ici, UPDATE)
		$entityManager->flush();

		return $this->redirectToRoute("test_browse");
	}
}
