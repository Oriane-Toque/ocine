<?php

namespace App\EventListener;

use App\Entity\Movie;
use App\Service\SlugService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MovieTitleSlugger
{
    private $slugger;

    public function __construct(SlugService $slugService)
    {
        $this->slugger = $slugService;
    }

    public function slugify(Movie $movie, LifecycleEventArgs $event): void
    {
        $movie->setSlug($this->slugger->slugConvert($movie->getTitle()));
    }
}
