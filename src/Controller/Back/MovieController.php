<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Form\Front\MovieType;
use App\Service\MessageGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * Lister les films
     *
     * @Route("/back/movie/browse", name="back_movie_browse", methods={"GET"})
     *
     * @param MovieRepository $movieRepository
     * @return Response
     */
    public function browse(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findByTitleAsc();

        return $this->render('back/movie/browse.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Afficher un film
     *
     * @Route("/back/movie/read/{slug}", name="back_movie_read", methods={"GET"})
     *
     * @param Movie|null $movie
     * @return Response
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
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $movie = new Movie();

        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $movie->setSlug($slugService->slugConvert($movie->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('back_movie_read', ['slug' => $movie->getSlug()]);
        }

        // Affiche le form
        return $this->render('back/movie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Editer un film
     * 
     * @Route("/back/movie/edit/{slug}", name="back_movie_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Movie $movie
     * @param MessageGenerator $messageGenerator
     * @return Response
     */
    public function edit(Request $request, Movie $movie, MessageGenerator $messageGenerator): Response
    {
        // 404 ?
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $movie->setSlug($slugService->slugConvert($movie->getTitle()));

            // dd($movie);

            $em = $this->getDoctrine()->getManager();
            // Pas de persist() pour 
            $em->flush();

            $this->addFlash('success', $messageGenerator->getRandomMessage());

            return $this->redirectToRoute('back_movie_read', ['slug' => $movie->getSlug()]);
        }

        // Affiche le form
        return $this->render('back/movie/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Supprimer un film
     * => en GET à convertir en POST ou mieux en DELETE
     * 
     * @Route("/back/movie/delete/{slug}", name="back_movie_delete", methods={"GET"})
     *
     * @param Movie|null $movie
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Movie $movie = null, EntityManagerInterface $entityManager): Response
    {
        // 404 ?
        if ($movie === null) {
            throw $this->createNotFoundException("Ce film n'existe pas");
        }

        $entityManager->remove($movie);
        $entityManager->flush();

        return $this->redirectToRoute("back_movie_browse");
    }
}
