<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController {

	/**
	 * @Route("/search/{title}", name="search_title", methods={"GET"})
	 */
	public function search($title, MovieRepository $movieRepository) {

		$movies = $movieRepository->searchMovieByTitleAsc($title, 'jolie');

		dd($movies);

	}
}