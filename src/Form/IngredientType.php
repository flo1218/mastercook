<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'ingredient.name.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'ingredient.price.label',
                'currency' => '',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(200),
                ],
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
            'data_class' => Ingredient::class,
        ]);
    }
}
