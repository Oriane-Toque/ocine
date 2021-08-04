<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use App\Entity\Review;
use App\Form\Front\ReviewType;
use App\Repository\CastingRepository;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{

	/**
	 * Liste Movies
	 *
	 * @Route("/", name="movie_list")
	 */
	public function list(MovieRepository $movieRepository, GenreRepository $genreRepository)
	{

		$movies = $movieRepository->findAllByTitle(
			[],
			[
				"releaseDate" => "DESC",
			]
		);

		// Tous les genres
		$genres = $genreRepository->findBy([], ['name' => 'ASC']);

		// dump($movies);


		// Accès aux paramètres de services depuis un contrôleur
		// @link https://symfony.com/doc/current/configuration.html#accessing-configuration-parameters
		dump($this->getParameter('kernel.project_dir'));
		dump($this->getParameter('app.message_generator_is_random'));

		return $this->render(
			"front/home/movies.html.twig",
			[
				"movies" => $movies,
				"genres" => $genres,
			]
		);
	}

	/**
	 * Read Movie
	 *
	 * @Route("/movie/{slug}", name="movie_read")
	 */
	public function read(Movie $movie = null, CastingRepository $castingRepository)
	{
		if ($movie === null) {
			throw $this->createNotFoundException("404");
		}

		$castings = $castingRepository->findAllByMovieOrderCreditQb($movie);

		dump($movie);
		dump($castings);

		return $this->render(
			"front/movie/movie.html.twig",
			[
				"movie" => $movie,
			]
		);
	}

	/**
	 * @Route("/movie/{slug}/review/add", name="add_review")
	 */
	public function addReview(Movie $movie, Request $request)
	{

		// Film non trouvé
		if ($movie === null) {
			throw $this->createNotFoundException("Film non trouvé");
		}

		// Nouvelle critique
		$review = new Review();

		// création d'un nouveau formulaire
		$form = $this->createForm(ReviewType::class, $review);

		// on effectue le traitement du formulaire
		$form->handleRequest($request);

		// dd($review);

		// on vérifie que le formulaire a été soumis et s'il est valide
		if ($form->isSubmitted() && $form->isValid()) {

			// Relation review <> movie
			$review->setMovie($movie);

			$reviewManager = $this->getDoctrine()->getManager();
			$reviewManager->persist($review);
			$reviewManager->flush();

			return $this->redirectToRoute("movie_read", ["slug" => $movie->getSlug()]);
		}

		// Affiche le formulaire
		return $this->render(
			"front/movie/movie_add_review.html.twig",
			[
				"title" => "Ajout critique",
				"form" => $form->createView(),
				"movie" => $movie,
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
