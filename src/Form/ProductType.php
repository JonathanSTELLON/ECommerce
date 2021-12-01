<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //on récupère l'entité associée dans le controller à savoir le Product $product
        $currentProduct = $builder->getData();

        //si je récupère un id ce la signifie que le $currentProduct est déjà en bdd donc on est dans la modif et on a pas besoin de la contrainte NotBlank()
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
        //si je n'ai pas d'id, on est dans l'ajout et on ajoute donc la contrainte NotBlank()
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
                'attr' => ['class' => 'input',
                            'id'=>'file-ip-1',
                            'onchange'=>'showPreview(event);'
            ],
                'mapped' => false, //on ne le lie pas à l'entité product et on remplira ce champ dans le controller 
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
