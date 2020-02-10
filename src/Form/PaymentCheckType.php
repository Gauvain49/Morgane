<?php

namespace App\Form;

use App\Entity\MgPaymentCheck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentCheckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('order_check', TextType::class, [
                'label' => 'Ordre auquel le chèque doit être rempli'])
            ->add('address_check', TextareaType::class, [
                'label' => 'Adresse à laquelle envoyer les paiements par chèque'])
            //->add('mode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgPaymentCheck::class,
        ]);
    }
}
