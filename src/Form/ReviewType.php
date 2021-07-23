<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add(
				'username',
				TextType::class,
				[
					'label' => 'Pseudo',
				]
			)
			->add(
				'email',
				EmailType::class,
				[
					'label' => 'E-mail',
				]
			)
			->add(
				'content',
				TextareaType::class,
				[
					'label' => 'Critique',
				]
			)
			->add(
				'rating',
				ChoiceType::class,
				[
					'label' => 'Avis',
					'placeholder' => 'Votre note sur 5...',
					'choices' =>
					[
						'Excellent (5)' => 5,
						'Très bon (4)' => 4,
						'Bon (3)' => 3,
						'Peut mieux faire (2)' => 2,
						'A éviter (1)' => 1,
					]
				]
			)
			->add(
				'reactions',
				ChoiceType::class,
				[
					'label' => 'Ce film vous a fais :',
					'expanded' => true,
					'multiple' => true,
					'choices' =>
					[
						'Rire' => 'smile',
						'Pleurer' => 'cry',
						'Réfléchir' => 'think',
						'Dormir' => 'sleep',
						'Rêver' => 'dream',
					]
				]
			)
			->add(
				'watchedAt',
				DateTimeType::class,
				[
					'label' => 'Vous 	avez vu ce film le :',
					'input' => 'datetime_immutable',
					'placeholder' => [
						'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
						'hour' => 'Heures', 'minute' => 'Minutes', 'second' => 'Secondes',
					]
				]
			);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Review::class,
		]);
	}
}
