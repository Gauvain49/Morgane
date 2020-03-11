<?php

namespace App\Form;

use App\Entity\MgCarriers;
use App\Entity\MgProducts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCarrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('width', TextType::class, [
                'label' => 'Largeur',
                'required' => false
            ])
            ->add('height', TextType::class, [
                'label' => 'Hauteur',
                'required' => false
            ])
            ->add('depth', TextType::class, [
                'label' => 'Profondeur',
                'required' => false
            ])
            ->add('weight', TextType::class, [
                'label' => 'poids',
                'required' => false
            ])
            ->add('additionnal_shipping_cost', MoneyType::class, [
                'label' => 'Frais de transport additionnels pour ce produit',
                'required' => false
            ])
            ->add('carrier', EntityType::class, [
                'label' => 'Transporteur',
                'class' => MgCarriers::class,
                'choice_label' => 'carrier_name',
                'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgProducts::class,
        ]);
    }
}
