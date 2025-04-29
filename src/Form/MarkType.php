<?php

namespace App\Form;

use App\Entity\Mark;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mark', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'recipe.vote.placeholder',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'choice_attr' => [
                    '1' => ['style' => 'color:red'],
                    '5' => ['style' => 'color:green'],
                ],
                'attr' => [
                    'class' => 'form-control form-select',
                    'aria-label' => 'test',
                ],
                'label' => 'recipe.vote.label',
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4 custom-btn bi bi-floppy2',
                ],
                'label' => 'recipe.vote.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mark::class,
        ]);
    }
}
