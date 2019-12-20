<?php

namespace App\Form;

use App\Entity\MgProperties;
use App\Form\PropertiesLangType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('properties', CollectionType::class, [
                'label_attr' => [
                    'style' => 'display: none;'
                ],
                'entry_type' => PropertiesLangType::class,
                'allow_add' => true
                ])
            ->add('position', IntegerType::class, [
                'label' => 'Position',
                'required' => false,
                'help' => 'Morgane gère automatiquement les position si vous ne remplissez rien.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgProperties::class,
        ]);
    }
}
