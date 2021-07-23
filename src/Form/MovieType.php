<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title')
			// Ici, pas besoin car géré automatiquement par notre code
			// ->add('createdAt')
			// ->add('updatedAt')
			// Avec DateType, pas besoin des heures et minutes
			->add('releaseDate', DateType::class, [
				'years' => range(date('Y') - 100, date('Y') + 10),
				// Date actuelle par défaut
				//'data' => new DateTime(),
				// Se besoin d'initialiser la date sur une date précise
				// 'data' => new DateTime('1986-04-12'),
			])
			->add('duration')
			->add('poster', UrlType::class)
			// Cette note sera calculée via les Reviews front
			// on ne la manipule pas manuellement
			// ->add('rating')
			// @link https://symfony.com/doc/current/reference/forms/types/entity.html
			->add(
				'genres',
				EntityType::class,
				[
					'class' => Genre::class,
					// option pour écrire une requête custom afin d'afficher les genres par ordre alphabétique
					// ou écrire requete dans repository puis return $genreRepository->findGenreByAsc() par exemple
					'query_builder' => function (GenreRepository $genreRepository) {
						return $genreRepository->createQueryBuilder('g')
							->orderBy('g.name', 'ASC');
					},
					'choice_label' => 'name',
					'multiple' => true,
					'expanded' => true,
				]
			);
	}

	/**
	 * Sorte de "__construct" pour les options par défaut du form
	 * (donc notamment de la balise HTML form qui sera rendue)
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Movie::class,
		]);
	}
}
