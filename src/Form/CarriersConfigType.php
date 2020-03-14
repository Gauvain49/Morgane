<?php

namespace App\Form;

use App\Entity\MgCarriersConfig;
use App\Entity\MgTaxes;
use App\Form\CarriersAmountCountriesType;
use App\Form\CarriersStepsDepartmentsType;
use App\Form\CarriersStepsType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                    'En fonction de la quantité' => 'qty'],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('taxe', EntityType::class, [
                'class' => MgTaxes::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.taxe_rate', 'DESC');
                },
                'choice_label' => 'taxe_rate',
                'choice_attr' => function($choiceValue, $key, $value) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['data-role' => $choiceValue->getTaxeRate()];
                }
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
        ;
        if ($options['required_departments']) {
            $builder
                ->add('stepsDeps', CollectionType::class, [
                    'label' => 'Tranches de prix HT',
                    'entry_type' => CarriersStepsDepartmentsType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'attr' => ['class' => 'row listCountry'],
                ])
            ;
        } else {
            $builder
                ->add('steps', CollectionType::class, [
                    'label' => 'Tranches de prix HT',
                    'entry_type' => CarriersStepsType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'attr' => ['class' => 'row listCountry'],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCarriersConfig::class,
            'required_departments' => false
        ]);
        $resolver->setAllowedTypes('required_departments', 'bool');
    }
}
