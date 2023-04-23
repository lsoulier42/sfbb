<?php

namespace App\Form\Topic;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
