<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('releaseDate')
            ->add('duration')
            ->add('poster')
            ->add('genres', EntityType::class,
							[
								'class' => Genre::class,
								// option pour écrire une requête custom afin d'afficher les genres par ordre alphabétique
								'query_builder' => function (GenreRepository $genreRepository) {
									return $genreRepository->createQueryBuilder('g')
																				 ->orderBy('g.name', 'ASC');
								},
								'choice_label' => 'name',
								'multiple' => true,
								'expanded' => true,
							])
        ;
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
