<?php

namespace App\Form\Back;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
							[
								'label' => 'E-mail',
							])
            ->add('roles', ChoiceType::class,
							[
								'label' => 'Rôle',
								'multiple' => true,
								'expanded' => true,
								'choices' => [
									// Label => valeur
									'Membre' => 'ROLE_USER',
									'Manager' => 'ROLE_MANAGER',
									'Administrateur' => 'ROLE_ADMIN',
								],
							])
            ->add('password', RepeatedType::class, 
							[
								'type' => PasswordType::class,
								'invalid_message' => 'Les mots de passe ne correspondent pas',
								'required' => true,
								'first_options'  => [
									'label' => 'Mot de passe',
									'help' => 'Minimum eight characters, at least one letter, one number and one special character',
								],
								'second_options' => ['label' => 'Répéter le mot de passe'],
							]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
