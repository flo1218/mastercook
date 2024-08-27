<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('fullName', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '2',
                'maxlength' => '50',
            ],
            'label' => 'registration.name.label',
            'label_attr' => [
                'class' => 'form-label mt-4',
            ],
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('pseudo', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '2',
                'maxlength' => '50',
            ],
            'required' => false,
            'label' => 'registration.pseudo.label',
            'label_attr' => [
                'class' => 'form-label mt-4',
            ],
        ])
        ->add('language', ChoiceType::class, [
            'choices' => [
                'Français' => 'fr',
                'Anglais' => 'en',
            ],
            'attr' => [
                'class' => 'form-select',
            ],
            'label' => 'registration.language.label',
            'label_attr' => [
                'class' => 'form-label mt-4',
            ],
        ])
        ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary custom-btn mt-4 bi bi-floppy2',
            ],
            'label' => 'app.edit.label',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
