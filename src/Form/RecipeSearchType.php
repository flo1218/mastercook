<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecipeSearchType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredients', EntityType::class, [
                'required' => true,
                'choice_label' => function (Ingredient $ingredient): string {
                    return $ingredient->getName();
                },
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
                    'class' => 'btn btn-primary custom-btn mt-4 bi bi-search',
                ],
                'label' => 'app.find.label',
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
        /*$resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);*/
    }
}
