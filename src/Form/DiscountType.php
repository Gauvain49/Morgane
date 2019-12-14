<?php

namespace App\Form;

use App\Entity\MgProducts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('discount', null, [
                'label' => 'Réduction à appliquer',
                'required' => true]
            )
            ->add('discount_type', ChoiceType::class, [
                'choices' => [
                    '€' => 'amount',
                    '%' => 'percent'
                ],
                'label' => 'Type de réduction'
            ])
            ->add('discount_on_taxe', ChoiceType::class, [
                'choices' => [
                    'TTC' => true,
                    'HT' => false
                ],
                'label' => 'Sur TTC ou HT'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgProducts::class,
        ]);
    }
}
