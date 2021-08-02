<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use App\Repository\MovieRepository;
use Twig\Environment as Twig;

class RandomMovieSubscriber implements EventSubscriberInterface
{

    /**
     * On appelle le service MovieRepository
     */
    private $movieRepository;

    /**
     * Twig
     */
    private $twig;

    public function __construct(MovieRepository $movieRepository, Twig $twig)
    {
        $this->movieRepository = $movieRepository;
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event)
    {
        // dump('Subscriber Movie');
        // dump($event);

        // Récupérer le controleur
        $controller = $event->getController();

        // avec les exceptions, le controleur n'est pas sous forme de tableau
        if (is_array($controller)) {
            // Récupérons le contrôleur, qui se trouve à l'index 0 du tableau
            // qui contient le contrôleur et la méthode à appeler
            $controller = $controller[0];

            // On récupère le nom de la classe de contrôleur
            $controllerClassName = (get_class($controller));

            if (strpos($controllerClassName, 'App\Controller') === false) {
                // On sort du suscriber
                return;
            }

            // 2. On va chercher un film au hasard
            // @todo Utiliser ORDER BY RAND() LIMIT 1
            // dans une requête custom dans le Respository

            // En attendant, on va faire un shuffle() sur tous les films
            $movies = $this->movieRepository->findAll();
            // On mélange, on prend le premier
            shuffle($movies);
            $randomMovie = $movies[0];
            // dump($randomMovie);

            // 3. On le transmet à Twig
            $this->twig->addGlobal('randomMovie', $randomMovie);

            dump('subscriber appelé !');
        }

        // Notre écouteur ne s'éxecute pas partout
        // uniquement depuis nos controleurs
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
