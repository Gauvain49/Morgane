<?php

namespace App\Form;

use App\Entity\MgCountries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('country_default')
            ->add('iso_code')
            ->add('zip_code_format')
            ->add('countriesLangs')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCountries::class,
        ]);
    }
}
