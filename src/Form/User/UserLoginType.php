<?php

namespace App\Form\User;

use App\Dto\User\UserLoginDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'required' => true,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'user.label.username'
                    ]
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'required' => true,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'user.label.password'
                    ]
                ]
            )
            ->add(
                'rememberMe',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'user.label.remember_me'
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'user.label.login',
                    'attr' => [
                        'class' => 'btn btn-primary px-5 m-2'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UserLoginDto::class,
                'csrf_protection' => true,
                'csrf_token_id' => 'authenticate'
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}