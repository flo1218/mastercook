<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RecipeType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'recipe.name.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank(),
                ],
            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'recipe.time.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1441),
                ],
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'recipe.nbPeople.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(51),
                ],
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5,
                ],
                'label' => 'recipe.difficulty.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThanOrEqual(5),
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '7',
                ],
                'label' => 'recipe.description.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\Length(['max' => 1000]),
                ],
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'recipe.price.label',
                'currency' => '',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1000),
                ],
            ])
            ->add('isPublic', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check',
                ],
                'required' => false,
                'label' => 'recipe.isPublic.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check',
                ],
                'required' => false,
                'label' => 'recipe.isFavorite.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'download_label' => "recipe.download-image.label",
                'delete_label' => "recipe.delete-image.label",
                'download_uri' => true,
                'asset_helper' => true,
                'imagine_pattern' => 'my_thumb',
                'label' => 'recipe.image.label',
            ])
            ->add('ingredients', EntityType::class, [
                'choice_label' => function (Ingredient $ingredient): string {
                    return $ingredient->getName().' ('.$ingredient->getPrice().' CHF)';
                },
                'row_attr' => ['class' => 'tinymce'],
                'class' => Ingredient::class,
                'multiple' => true,
                'expanded' => true,
                'label' => 'header.ingredient.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'query_builder' => function (EntityRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user', $this->security->getToken()->getUser());
                },
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary custom-btn mt-4 bi bi-floppy2',
                ],
                'label' => 'app.save.label'
            ])
            ->add('cancel', SubmitType::class, [
                'attr' => [
                    'formnovalidate' => 'formnovalidate',
                    'class' => 'btn btn-light mt-4 ms-2 custom-btn bi bi-x-circle-fill',
                ],                
                'label' => 'app.cancel.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
