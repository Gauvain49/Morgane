<?php

namespace App\Form;

use App\Entity\MgCarriersSteps;
use App\Entity\MgCountries;
use App\Form\CarriersAmountCountriesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarriersStepsType extends AbstractType
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
            ->add('amountCountries', CollectionType::class, [
                'label' => 'Pays',
                'entry_type' => CarriersAmountCountriesType::class,
                'allow_add' => true,
                'prototype_name' => '__country__'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCarriersSteps::class,
        ]);
    }
}
