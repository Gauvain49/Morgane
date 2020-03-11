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
                'required' => false
                //'label_attr' => ['class' => 'col-sm-6 col-form-label', 'style' => 'background-color: #99C7FF;'],
                //'attr' => ['style' => 'width: 100px;']
            ])
            ->add('step_max', TextType::class, [
                'label' => 'jusqu\'à une valeur < à ',
                'required' => false
                //'label_attr' => ['class' => 'col-sm-6 col-form-label', 'style' => 'background-color: #99C7FF;'],
                //'attr' => ['style' => 'width: 100px;']
            ])
            //->add('config')
            /*->add('step_country', EntityType::class, [
                'label' => false,
                'class' => MgCountries::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                            ->join('c.countriesLangs', 'l')
                            ->addSelect('l')
                            ->where('l.lang = 1')
                            ->andWhere('c.active = 1');
                },
            'choice_label' => 'countriesLangs[0].country_name',
            'expanded' => true,
            'multiple' => true,
            ])*/
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
