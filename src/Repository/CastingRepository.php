<?php

namespace App\Repository;

use App\Entity\Casting;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Casting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Casting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Casting[]    findAll()
 * @method Casting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CastingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Casting::class);
    }

    // /**
    //  * @return Casting[] Returns an array of Casting objects
    //  */

		/**
		 * Récupère tous les castings d'un film donné
		 * Avec DQL
		 *
		 * @param Movie[] $movie
		 * @return Casting[]
		 */
		public function findAllByMovieOrderCredit(Movie $movie): array
    {
			$entityManager = $this->getEntityManager();

			$query = $entityManager->createQuery(
					'SELECT c, p
					FROM App\Entity\Casting c
					INNER JOIN c.person p
					WHERE c.movie = :movie
					ORDER BY c.creditOrder ASC'
			)->setParameter('movie', $movie);

			return $query->getResult();
    }

		/**
		 * Récupère tous les castings d'un film donné
		 * Avec Query Builder
		 *
		 * @param Movie[] $movie
		 * @return Casting[]
		 */
		public function findAllByMovieOrderCreditQb(Movie $movie): array
    {
        return $this->createQueryBuilder('c')
			->innerJoin('c.person', 'p')
            ->andWhere('c.movie = :movie')
            ->setParameter('movie', $movie)
            ->orderBy('c.creditOrder', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Casting
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
