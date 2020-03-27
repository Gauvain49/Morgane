<?php

namespace App\Form;

use App\Entity\MgCarriersStepsRegions;
use App\Entity\MgCountries;
use App\Form\CarriersAmountRegionsType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarriersStepsRegionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('step_min', TextType::class, [
                'label' => 'Pour une valeur >= à ',
                'label_attr' => ['class' => 'col-form-label step_min'],
                'required' => false
            ])
            ->add('step_max', TextType::class, [
                'label' => 'jusqu\'à une valeur < à ',
                'label_attr' => ['class' => 'col-form-label step_max'],
                'required' => false
            ])
            ->add('amountRegions', CollectionType::class, [
                'label' => 'Pays',
                'entry_type' => CarriersAmountRegionsType::class,
                'allow_add' => true,
                'prototype_name' => '__region__'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCarriersStepsRegions::class,
        ]);
    }
}
