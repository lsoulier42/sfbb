<?php

namespace App\Form\Topic;

use App\Dto\Topic\TopicDto;
use App\Entity\Topic;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopicType extends AbstractType
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
            )
            ->add(
                'content',
                CKEditorType::class,
                [
                    'required' => true,
                    'label' => 'topic.label.content'
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'topic.label.submit'
                ]
            );
    }
}