<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', PasswordType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'label' => 'registration.password.label',
            'label_attr' => [
                'class' => 'form-label mt-4',
            ],
        ])
        ->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => ['attr' => ['class' => 'password-field']],
            'first_options' => [
                'label' => 'registration.new-password.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ],
            'second_options' => [
                'label' => 'registration.repeat-password.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ],
            'invalid_message' => 'validators.notmatching-password.label',
        ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4 custom-btn mt-4 bi bi-floppy2',
                ],
                'label' => 'registration.update-password.label',
            ])
        ;
    }
}
