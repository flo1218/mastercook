<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
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
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'registration.email.label',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options' => [
                    'label' => 'registration.password.label',
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
                'label' => 'registration.submit.label',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
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
