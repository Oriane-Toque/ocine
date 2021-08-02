<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\MovieRepository;
use App\Service\OmdbApi;
use Doctrine\ORM\EntityManagerInterface;

class MoviePosterCommand extends Command
{
    // nom de la commande, bonne pratique on la préfixe avec "app"
    protected static $defaultName = 'app:movie:poster';
    // description de la commande
    protected static $defaultDescription = 'Fetch movie posters from OMDB API';

    // les services nécessaires à notre commande
    private $movieRepository;
    private $entityManager;
    private $omdbApi;

    // ... qu'on récupère en injection de dépendances ici (récupération des objets nécessaires de l'extérieur sans passer par l'instanciation)
    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManager, OmdbApi $omdbApi)
    {
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
        $this->omdbApi = $omdbApi;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // message d'aide supplémentaire
            ->addArgument('title', InputArgument::OPTIONAL, 'Movie title to fetch')
            ->addOption('dump', 'd', InputOption::VALUE_NONE, 'Dump title movies');
    }

    // objet input permet de récupérer l'entrée
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // permet de fioritures graphiques dans le terminal
        $io = new SymfonyStyle($input, $output);
        // on récupềre l'argument "title" si présent
        $title = $input->getArgument('title');

        if ($title) {
            // si un titre est présent, on ne traite que ce film
            $io->note(sprintf('Movie to fetch: %s', $title));
            $movie = $this->movieRepository->findOneBy(['title' => $title]);
            // on colle movie dans un tableau $movies (pour simplifier le foreach plus bas)
            $movies = [$movie];
        } else {
            // sinon on traite tous les films
            $io->note(sprintf('Fetching all movies'));
            $movies = $this->movieRepository->findAll();
        }

        if ($input->getOption('dump')) {
            $io->info('Fetching ' . $title);
        }

        // la logique métier / l'objectif de la commande
        // on récupère les films concernés si on bien récupéré l'argument
        // dd($movies);
        // on boucle dessus et ensuite on va chercher les données associées sur OMDB API
        foreach ($movies as $movie) {
            // => conversion à faire JSON => Array
            $movieData = $this->omdbApi->fetchPoster($movie->getTitle());

            if (!$movieData) {
                $io->warning('Poster not found :scream: ' . $movie->getTitle());
            }
            // On met à jour l'url du poster dans le film
            $movie->setPoster($movieData);
        }

        $this->entityManager->flush();

        $io->success('All movie fetched ! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
