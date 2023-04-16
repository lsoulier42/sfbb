<?php

namespace App\Form\Forum;

use App\Entity\Category;
use App\Entity\Forum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Forum::class,
                'category' => null
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $forum = $options['data'];
        $isEdit = $forum->getId() !== null;
        $builder
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'data' => $isEdit ? $forum->getCategory() : $options['category'],
                    'required' => true,
                    'label' => 'forum.label.category',
                    'placeholder' => 'forum.label.select_category'
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'forum.label.title'
                ]
            )
            ->add(
                'subTitle',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'forum.label.sub_title'
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => $isEdit ? 'global.label.update' : 'global.label.create'
                ]
            );
    }
}
