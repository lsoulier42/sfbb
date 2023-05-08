<?php

namespace App\Form\User;

use App\Dto\User\UserEditProfileDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditProfileType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UserEditProfileDto::class
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'label' => 'user.label.email',
                ]
            )
            ->add(
                'currentPassword',
                PasswordType::class,
                [
                    'required' => false,
                    'label' => 'user.label.current_password'
                ]
            )
            ->add(
                'newPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'user.register.password.error',
                    'required' => false,
                    'first_options' =>
                        [
                            'label' => 'user.label.new_password',
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
                BirthdayType::class,
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
                'submit',
                SubmitType::class,
                [
                    'label' => 'global.label.edit',
                ]
            );
    }
}