<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentProduct = $builder->getData();

        if($currentProduct->getId()){
            $constraints = [
                new File([
                'maxSize' => '500k',
                'mimeTypes' => [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                ],
                'mimeTypesMessage' => 'Veuillez entrer un type de fichier valide (jpeg ou png)',
                ])
            ];
        }
        else{
            $constraints = [
                new File([
                    'maxSize' => '500k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/jpg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Veuillez entrer un type de fichier valide (jpeg ou png)',
                ]),
                new NotBlank()
            ];
        }
        
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['class' => 'input']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'attr' => ['class' => 'input']
            ] )
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'input']
            ])
            ->add('thumbnail', FileType::class, [
                'data_class' => null,
                'label' => 'Photo',
                'attr' => ['class' => 'input'],
                'mapped' => false,
                'required' => false,
                'constraints' => $constraints
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
