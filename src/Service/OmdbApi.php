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

    public function fetch(): array
    {
        $response = $this->client->request(
            'GET',
            'https://www.omdbapi.com/?apiKey=83bfb8c6&t=rambo'
        );

        // on convertit le tableau en JSON
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }
}
