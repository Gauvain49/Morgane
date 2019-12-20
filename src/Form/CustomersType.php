<?php

namespace App\Form;

use App\Entity\MgCustomers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('compagny')
            ->add('birthday')
            ->add('notes')
            ->add('user')
            ->add('customer_group')
            ->add('gender')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCustomers::class,
        ]);
    }
}
