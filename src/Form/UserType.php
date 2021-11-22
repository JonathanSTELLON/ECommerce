<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Saisissez votre prénom'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Saisissez votre nom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Saisissez votre email',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Erreur de saisie de confirmation de mot de passe',
                'required' => true,
                'first_options'  => ['label' => 'Saisissez votre mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez un mot de passe'
                    ]),
                    new Length(['min' => 8,
                                'minMessage' => 'Votre mot de passe doit contenir au moins 8 caractères',
                    ]),
                ]
            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
