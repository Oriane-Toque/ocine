<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * Lister les films
     *
     * @Route("/back/movie/browse", name="back_movie_browse", methods={"GET"})
     */
    public function browse(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAllOrderedByTitleAscQb();

        return $this->render('back/movie/browse.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Afficher un film
     *
     * @Route("/back/movie/read/{id}", name="back_movie_read", methods={"GET"})
     */
    public function read(Movie $movie = null): Response
    {
        // 404 ?
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        return $this->render('back/movie/read.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * Ajouter un film
     *
     * @Route("/back/movie/add", name="back_movie_add", methods={"GET", "POST"})
     */
    public function add(): Response
    {
    }

    /**
     * Editer un film
     * 
     * @Route("/back/movie/edit/{id}", name="back_movie_edit", methods={"GET","POST"})
     */
    public function edit(): Response
    {
    }

    /**
     * Supprimer un film
     * => en GET à convertir en POST ou mieux en DELETE
     * 
     * @Route("/back/movie/delete/{id<\d+>}", name="back_movie_delete", methods={"GET"})
     */
    public function delete(): Response
    {
    }
}
