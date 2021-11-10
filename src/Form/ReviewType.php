<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $grades = [0,1,2,3,4,5];
        
        $builder
            ->add('nickname', TextType::class, [
                'label' => 'Pseudo'
            ])
            ->add('content', TextType::class, [
                'label' => 'Votre avis'
            ])
            ->add('grade', ChoiceType::class, [
                'label' => 'Votre note',
                'choices' => $grades
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
