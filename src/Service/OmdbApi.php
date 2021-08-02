<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApi
{
    /**
     * On récupère un client http pour exécuter des requetes
     */
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetch(string $title): array
    {
        $response = $this->client->request(
            'GET',
            'https://www.omdbapi.com/?apiKey=83bfb8c6&t=' . $title,
        );

        // on convertit le tableau en JSON
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }

    /**
     * Renvoie l'URL du poster
     *
     * @param string $title Titre du film
     * @return null|string
     */
    public function fetchPoster(string $title)
    {
        $content = $this->fetch($title);

        // Clé Poster existe ?
        if (array_key_exists('Poster', $content)) {
            return $content['Poster'];
        }

        return null;
    }
}
