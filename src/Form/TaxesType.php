<?php

namespace App\Form;

use App\Entity\MgTaxes;
use App\Form\TaxesLangType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taxesLangs', CollectionType::class,
                [
                'label_attr' => [
                    'style' => 'display: none;'
                ],
                    'entry_type' => TaxesLangType::class,
                    'allow_add' => true
                ])
            ->add('taxe_rate', PercentType::class, [
                'label' => 'Taux'
            ])
            ->add('taxe_active', CheckboxType::class, [
                'label' => 'Active',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger',
                    'data-style' => 'btn-round',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ],
                'data' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgTaxes::class,
        ]);
    }
}
