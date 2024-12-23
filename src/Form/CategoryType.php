<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'autofocus' => null,
            ],
            'required' => false,
            'label' => 'ingredient.name.label',
            'label_attr' => [
                'class' => 'form-label mt-4 required',
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
            'data_class' => Category::class,
        ]);
    }
}
