<?php

namespace App\Form\Topic;

use App\Dto\Topic\PostDto;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractMessageType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => PostDto::class
            ]
        );
    }
}