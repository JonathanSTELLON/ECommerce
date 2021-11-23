<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReloadAvatarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('size', ChoiceType::class, [
            'label' => 'Taille',
            'choices' => array_combine(range(3,8), range(3,8))
        ])
        ->add('color', ChoiceType::class, [
            'label' => 'Couleurs',
            'choices' => array_combine(range(2,5), range(2,5))
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
