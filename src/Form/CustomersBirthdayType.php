<?php

namespace App\Form;

use App\Entity\MgCustomers;
use App\Entity\MgCustomersGroups;
use App\Form\CustomersAddressesType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomersBirthdayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime();
        $builder
            ->add('birthday', BirthdayType::class, [
                    'label' => 'Date de naissance',
                    'placeholder' => ['year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'],
                    'widget' => 'choice',
                    //'required' => false,
                    'years' => range($date->format('Y')-18, $date->format('Y') - 120)
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCustomers::class,
        ]);
    }
}
