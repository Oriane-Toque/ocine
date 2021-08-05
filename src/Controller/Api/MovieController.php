<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class MovieController extends AbstractController
{
    /**
     * Get movies collection
     * 
     * @Route("/api/movies", name="api_movies_get", methods={"GET"})
     */
    public function getCollection(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        // on demande à symfony de serializer nos entités sous forme json
        return $this->json($movies, Response::HTTP_OK, [], ['groups' => 'movies_get']);
    }

    /**
     * Get a movie by id
     * 
     * @Route("/api/movies/{id<\d+>}", name="api_movies_get_item", methods="GET")
     */
    public function getItem(Movie $movie): Response
    {
        return $this->json(['movie' => $movie], Response::HTTP_OK, [], ['groups' => 'movies_get']);
    }

    /**
     * Add movie collection
     * 
     * @Route("/api/movies", name="api_movies_post", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        // on récupère le contenu de la requete (du JSON)
        $data = $request->getContent();

        // on deserialize le JSON vers une entité Movie
        $movie = $serializer->deserialize($data, Movie::class, 'json');

        $errors = $validator->validate($movie);

        if (count($errors) > 0) {

            return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->persist($movie);
        $entityManager->flush();

        // REST nous demande un statut 201 et un header location: url
        return $this->redirectToRoute('api_movies_get_item', ['id' => $movie->getId()], Response::HTTP_CREATED);
    }

    /**
     * Edit a movie
     * 
     * @Route("/api/movies/{id<\d+>}", name="api_movies_edit", methods={"PUT", "PATCH"})
     */
    public function edit(Request $request, Movie $movie = null, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        if (null === $movie) {
            return $this->json(['message' => 'Film introuvable !'], Response::HTTP_NOT_FOUND);
        }

        // @todo Pour PUT, s'assurer qu'on ait un certain nombre de champs
        // @todo Pour PATCH, s'assurer qu'on au moins un champ
        // sinon => 422 HTTP_UNPROCESSABLE_ENTITY
        // @todo Conditionner le message de retour au cas où
        // l'entité ne serait pas modifiée

        $data = $request->getContent();

        $movie = $serializer->deserialize($data, Movie::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $movie]);

        $errors = $validator->validate($movie);

        if (count($errors) > 0) {

            $newErrors = [];

            foreach ($errors as $error) {
                $newErrors[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $newErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em->flush();

        return $this->json(['movie' => $movie], Response::HTTP_ACCEPTED, ['Location' => $this->generateUrl('api_movies_get_item', ['id' => $movie->getId()])], ['groups' => 'movies_get']);
    }

    /**
     * Delete a movie
     * 
     * @Route("/api/movies/{id<\d+>}", name="api_movies_delete", methods={"DELETE"})
     */
    public function delete(Movie $movie = null, EntityManagerInterface $em): Response
    {
        if (null === $movie) {
            return $this->json(['message' => 'Film introuvable !'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($movie);
        $em->flush();

        return $this->json(['message' => 'Le film a bien été supprimé !'], Response::HTTP_OK);
    }
}
