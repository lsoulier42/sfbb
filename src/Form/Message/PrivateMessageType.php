<?php

namespace App\Form\Message;

use App\Dto\Message\SendMessageDto;
use App\Entity\User;
use App\Repository\UserRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrivateMessageType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => SendMessageDto::class,
                'with_recipient' => true,
                'with_title' => true,
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['with_title']) {
            $builder->add(
                'title',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'message.label.subject',
                    'attr' => ['maxlength' => 255],
                ]
            );
        }

        if ($options['with_recipient']) {
            $builder->add(
                'recipient',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'username',
                    'label' => 'message.label.recipient',
                    'required' => true,
                    'query_builder' => static fn (UserRepository $userRepository) => $userRepository
                        ->createQueryBuilder('u')
                        ->orderBy('u.username', 'ASC'),
                ]
            );
        }

        $builder
            ->add(
                'content',
                CKEditorType::class,
                [
                    'required' => true,
                    'empty_data' => '',
                    'label' => 'message.label.content',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'message.label.send',
                ]
            );
    }
}
