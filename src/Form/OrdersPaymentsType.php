<?php

namespace App\Form;

use App\Entity\MgOrdersPayments;
use App\Entity\MgPaymentsModes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersPaymentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('payment_type')
            ->add('payment_amount', MoneyType::class, [
                'label' => 'Montant'
            ])
            //->add('payment_date')
            ->add('info_transaction', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false
            ])
            //->add('payment_order')
            ->add('payment_mode', EntityType::class, [
                'label' => 'Mode de paiement',
                'class' => MgPaymentsModes::class,
                'choice_label' => 'title'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgOrdersPayments::class,
        ]);
    }
}
