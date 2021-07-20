<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */

		/**
		 * Récupère tous les films dans l'ordre alphabétique
		 * Avec QueryBuilder
		 *
		 * @return Movie[]
		 */
		public function findByTitleAsc(): array
    {
				// on crée un objet de type Query Builder, sur l'entité Movie
				// 'm' = alias pour l'entité Movie
        return $this->createQueryBuilder('m')
            ->orderBy('m.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

		/**
		 * Récupère tous les films dans l'ordre alphabétique
		 * Avec DQL
		 *
		 * @return Movie[]
		 */
    public function findAllByTitle(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT m
            FROM App\Entity\Movie m
            ORDER BY m.title ASC'
        );

        return $query->getResult();
    }

		/**
		 * Recherche des films et les renvois par ordre alphabétique
		 * Avec QueryBuilder
		 *
		 * @param string $title
		 * @param string $keywords
		 * @return Movie[]
		 */
		public function searchMovieByTitleAsc($title, $keywords = null): array
    {
				// on crée un objet de type Query Builder, sur l'entité Movie
				// 'm' = alias pour l'entité Movie
       return $this->createQueryBuilder('m')
			 			/* renvoie tous les films à partir de la première lettre du titre */
						->where('m.title > :title')
						->setParameter('title', $title)
						/* dans le cas ou m.title strictement égal au titre renseigné */
						// ->orWhere('m.title = :title')
						// ->setParameter('title', $title)
						/* dans le cas ou il y a keywords renseignés renvois les films qui s'y referrent  */
						->orWhere("MATCH_AGAINST(m.title) AGAINST(:keywords boolean)>0")
						->setParameter('keywords', $keywords)
						->orderBy('m.title', 'ASC')

            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
