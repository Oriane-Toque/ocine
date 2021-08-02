<?php

namespace App\EventListener;

use App\Entity\Movie;
use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Service\SlugService;

class MovieTitleSlugger
{
    private $slugger;

    public function __construct(SlugService $slugService)
    {
        $this->slugger = $slugService;
    }

    public function prePersist(Movie $movie, LifecycleEventArgs $event)
    {
        $movie->setSlug($this->slugger->slugConvert($movie->getTitle()));
    }

    public function preUpdate(Movie $movie, LifecycleEventArgs $event)
    {
        $movie->setSlug($this->slugger->slugConvert($movie->getTitle()));
    }
}
