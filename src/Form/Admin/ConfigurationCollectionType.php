<?php

namespace App\Form\Admin;

use App\Dto\Admin\ConfigurationCollection;
use InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationCollectionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => ConfigurationCollection::class
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $collection = $options['data'];
        if (!$collection instanceof ConfigurationCollection) {
            throw new InvalidArgumentException("Wrong configuration collection");
        }
        foreach ($collection->getConfigurations() as $configuration) {
            $key = $configuration->getConfigKey();
            $builder
                ->add(
                    $key,
                    TextType::class,
                    [
                        'label' => $configuration->getConfigKey(),
                        'required' => true,
                        'mapped' => false,
                        'data' => $configuration->getConfigValue()
                    ]
                );
            $builder->get($key)->addModelTransformer(
                new CallbackTransformer(
                    static function ($el) {
                        return $el;
                    },
                    static function ($el) use ($configuration) {
                        $configuration->setConfigValue($el);
                        return $el;
                    }
                )
            );
        }
        $builder->add(
            'submit',
            SubmitType::class
        );
    }
}
