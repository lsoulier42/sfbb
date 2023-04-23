<?php

namespace App\Form\Topic;

use App\Dto\Topic\TopicDto;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopicType extends AbstractMessageType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => TopicDto::class
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'topic.label.title',
                    'attr' => [
                        'class' => 'w-100'
                    ]
                ]
            );
    }
}
