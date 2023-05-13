<?php

namespace App\Form\Admin;

use App\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Configuration::class
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'configKey',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'configuration.label.title'
                ]
            )
            ->add(
                'configValue',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'configuration.label.value'
                ]
            )
            ->add(
                'submit',
                SubmitType::class
            );
    }
}