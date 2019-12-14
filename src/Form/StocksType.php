<?php

namespace App\Form;

use App\Entity\MgProducts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StocksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité en stock',
                'required' => false
            ])
            ->add('sell_out_of_stock', CheckboxType::class, [
                'label' => 'Accepter les commandes',
                //'data' => true,
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger',
                    'data-style' => 'btn-round',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ],
                'required' => false
            ])
            ->add('stock_alert', IntegerType::class, [
                'label' => 'Seuil d\'alerte',
                'help' => 'Quantité critique nécessitant un réapprovisionnement.',
                'required' => false,
                'attr' => ['style' => "width: 100px;"]
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
