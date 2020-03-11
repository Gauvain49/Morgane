<?php

namespace App\Form;

use App\Entity\MgCarriersAmountCountries;
use App\Form\DataTransformer\CountryToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarriersAmountCountriesType extends AbstractType
{
    private $transformer;

    public function __construct(CountryToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country_amount', MoneyType::class, [
                //'label_attr' => ['class' => 'col-sm-6 col-form-label'],
                //'attr' => ['style' => 'width: 100px;']
            ])
            //->add('carrier_step')
            ->add('step_country', HiddenType::class, [])
            ->get('step_country')
                ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCarriersAmountCountries::class,
        ]);
    }
}
