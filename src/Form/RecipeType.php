<?php

namespace App\Form;

use App\Entity\Category;
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
    private Security $security;

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
                    'autofocus' => null,
                ],
                'required' => false,
                'label' => 'recipe.name.label',
                'label_attr' => [
                    'class' => 'form-label mt-4 required',
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                ],
            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'onkeypress' => 'return isNaN(event.key) ? false : true',
                ],
                'required' => false,
                'label' => 'recipe.time-minute.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\GreaterThanOrEqual(0),
                    new Assert\LessThanOrEqual(1440),
                ],
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'onkeypress' => 'return isNaN(event.key) ? false : true',
                ],
                'required' => false,
                'label' => 'recipe.nbPeople.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\GreaterThanOrEqual(0), // Validation côté serveur
                    new Assert\LessThan(51),
                ],
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5,
                ],
                'required' => false,
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
                'required' => false,
                'label' => 'recipe.description.label',
                'label_attr' => [
                    'class' => 'form-label mt-4 required',
                ],
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'step' => 'any',
                ],
                'required' => false,
                'label' => 'recipe.price.label',
                'currency' => '',
                'html5' => true,
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
                'download_label' => 'recipe.download-image.label',
                'delete_label' => 'recipe.delete-image.label',
                'allow_delete' => true,
                'download_uri' => false,
                'asset_helper' => true,
                'label' => 'recipe.image.label',
            ])
            ->add('ingredients', EntityType::class, [
                'choice_label' => function (Ingredient $ingredient): string {
                    return $ingredient->getName();
                },
                'attr' => [
                    'class' => 'form-control',
                ],
                'placeholder' => '',
                'autocomplete' => true,
                'required' => false,
                'class' => Ingredient::class,
                'multiple' => true,
                'expanded' => false,
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
            ->add('category', EntityType::class, [
                'choice_label' => function (Category $category): string {
                    return $category->getName();
                },
                'placeholder' => '',
                'autocomplete' => true,
                'required' => false,
                'class' => Category::class,
                'attr' => [
                    'class' => 'form-control',
                ],
                'multiple' => false,
                'expanded' => false,
                'label' => 'recipe.category.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'query_builder' => function (EntityRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('i')
                        ->where('i.user = :user or i.is_internal = 1')
                        ->orderBy('i.is_internal', 'DESC') // Trier les catégories internes en premier
                        ->addOrderBy('CASE WHEN i.is_internal = 1 THEN 1 ELSE i.name END', 'ASC')
                        ->setParameter('user', $this->security->getToken()->getUser());
                },
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary custom-btn mt-4 bi bi-floppy2',
                ],
                'label' => 'app.save.label',
            ])
            ->add('cancel', SubmitType::class, [
                'attr' => [
                    'formnovalidate' => 'formnovalidate',
                    'class' => 'btn btn-light mt-4 ms-2 custom-btn bi bi-x-circle',
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
