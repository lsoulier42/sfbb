<?php

namespace App\Form\User;

use App\Dto\User\MemberFilterDto;
use App\Enum\RoleEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => MemberFilterDto::class,
                'method' => 'GET'
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
                    'required' => false,
                    'label' => 'user.label.username',
                    'attr' => [
                        'placeholder' => 'Nom d\'utilisateur'
                    ]
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'user.label.last_name',
                    'attr' => [
                        'placeholder' => 'Nom'
                    ]
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'user.label.city',
                    'attr' => [
                        'placeholder' => 'Ville'
                    ]
                ]
            )
            ->add(
                'role',
                EnumType::class,
                [
                    'required' => false,
                    'label' => 'user.label.role',
                    'class' => RoleEnum::class,
                    'placeholder' => 'user.member_list.all_roles',
                    'choice_label' => static fn (RoleEnum $role): string => $role->getTransKey()
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'global.label.filter'
                ]
            );
    }
}
