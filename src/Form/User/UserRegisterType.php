<?php

namespace App\Form\User;

use App\Dto\User\UserRegisterDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UserRegisterDto::class
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'user.label.username',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'label' => 'user.label.email',
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'user.register.password.error',
                    'required' => true,
                    'first_options' =>
                        [
                            'label' => 'user.label.password',
                        ],
                    'second_options' =>
                        [
                            'label' => 'user.label.confirm_password',
                        ],
                ]
            )
            ->add(
                'firstName',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'user.label.first_name',
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'user.label.last_name',
                ]
            )
            ->add(
                'birthDate',
                DateType::class,
                [
                    'required' => false,
                    'label' => 'user.label.birth_date',
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'user.label.city',
                ]
            )
            ->add(
                'agreeTerms',
                CheckboxType::class,
                [
                    'label' => 'user.label.agree_terms',
                    'required' => true
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'user.label.register',
                ]
            );
    }
}
