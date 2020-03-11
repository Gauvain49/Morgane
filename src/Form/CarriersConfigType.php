<?php

namespace App\Form;

use App\Entity\MgCarriersConfig;
use App\Form\CarriersAmountCountriesType;
use App\Form\CarriersStepsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarriersConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('billing_on', ChoiceType::class, [
                'label' => 'Facturation',
                'choices' => [
                    'En fonction du prix total' => 'price',
                    'En fonction du poids' => 'weight',
                    'En fonction de ala quantité' => 'qty'],
                'expanded' => true,
                'multiple' => false,
                'data' => 'price'
            ])
            ->add('out_of_range', ChoiceType::class, [
                'label' => 'Si hors tranche',
                'choices' => [
                    'Port gratuit' => 'free',
                    'Prendre la plus grande tranche' => 'hit'],
                'expanded' => false,
                'multiple' => false,
                'data' => 'free'
            ])
            ->add('steps', CollectionType::class, [
                'label' => 'Tranches de prix',
                'entry_type' => CarriersStepsType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => ['class' => 'row listCountry'],
            ])
            /*->add('amountCountries', CollectionType::class, [
                'label' => 'Pays',
                'entry_type' => CarriersAmountCountriesType::class,
                'allow_add' => true
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCarriersConfig::class,
        ]);
    }
}
