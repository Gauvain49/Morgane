<?php

namespace App\Form;

use App\Entity\MgCarriersStepsDep;
use App\Entity\MgCountries;
use App\Form\CarriersAmountDepartmentsType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarriersStepsDepartmentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('step_min', TextType::class, [
                'label' => 'Pour une valeur >= à ',
                'required' => false
            ])
            ->add('step_max', TextType::class, [
                'label' => 'jusqu\'à une valeur < à ',
                'required' => false
            ])
            ->add('amountDepartments', CollectionType::class, [
                'label' => 'Pays',
                'entry_type' => CarriersAmountDepartmentsType::class,
                'allow_add' => true,
                'prototype_name' => '__department__'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCarriersStepsDep::class,
        ]);
    }
}
