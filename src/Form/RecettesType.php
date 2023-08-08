<?php

namespace App\Form;


use App\Entity\Recettes;
use App\Entity\Ingredients;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use App\Repository\IngredientsRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecettesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                    'minlength' => 2,
                    'maxlength' => 50
                ],
                'constraints' => [
                   new Assert\NotBlank(),
                   new Assert\Length(min: 2, max: 50)
                ]
            ])

            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                ],
                'label' => 'Prix',
                'constraints' => [
                    new Assert\NotNull(),
                    new Assert\Positive
                ]
            ])

            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                    'min' => 1,
                    'max' => 1440
                ],
                'label' => 'Temps de Préparation (en min)',
                'required' => false,
                'constraints' => [
                    new Assert\Positive,
                    new Assert\LessThan(1441)
                ]
            ])
            ->add('nbr_personne', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                    'min' => 1,
                    'max' => 50
                ],
                'label' => 'Nombre de Personnes pour le plat',
                'required' => false,
                'constraints' => [
                    new Assert\Positive,
                    new Assert\LessThanOrEqual(50)
                ]
            ])

            ->add('difficulty', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 20
                ],
                'required' => false,
                'label' => 'Difficulté pour confectionner le plat',
                'constraints' => [
                    new Assert\Positive,
                    new Assert\LessThanOrEqual(20)
                    ]
            ])

            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                ],
                'label' => 'Description du plat',
                'constraints' => [
                    new Assert\NotBlank()
                    ]
            ])

            ->add('ingredients', EntityType::class, [
                    'class' => Ingredients::class,
                    'query_builder' => function (IngredientsRepository $er): QueryBuilder {
                        return $er->createQueryBuilder('ingredients')
                            ->orderBy('ingredients.nom', 'ASC');
                    },
                    'label' => 'Les ingredients disponibles',
                    'attr' => [
                        
                    ],
                    'choice_label' => 'nom',
                    'multiple' => true,
                    'expanded' => true   
            ])

            ->add('isFavorite', ChoiceType::class, [
                'choices'  => [
                    'Oui' => true,
                    'Non' => false
                ],
                'attr' => [
                    'class' => 'form-control mb-4',
                ],
                'label' => 'Est-ce un plat Favori ?'
            ])

            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recettes::class,
        ]);
    }
}
