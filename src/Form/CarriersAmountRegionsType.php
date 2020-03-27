<?php

namespace App\Form;

use App\Entity\MgCarriersAmountRegions;
use App\Form\DataTransformer\RegionToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarriersAmountRegionsType extends AbstractType
{
    private $transformer;

    public function __construct(RegionToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('region_amount', MoneyType::class, [
            ])
            ->add('step_region', HiddenType::class, [])
            ->get('step_region')
                ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCarriersAmountRegions::class,
        ]);
    }
}
