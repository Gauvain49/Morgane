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

class CustomersAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime();
        $builder
            ->add('compagny', TextType::class, [
                'label' => 'Societé',
                'required' => false])
            ->add('birthday', BirthdayType::class, [
                    'label' => 'Date de naissance (en option)',
                    'placeholder' => ['year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'],
                    'required' => false,
                    'years' => range($date->format('Y'), $date->format('Y') - 120)
                ]
            )
            /*->add('addresses', CollectionType::class, [
                'label' => 'Votre adresse de facturation',
                'entry_type' => CustomersAddressesType::class,
                'allow_add' => false,
                'allow_delete' => false,
            ])*/

            //->add('notes')
            //->add('user')
            //->add('customer_group')
            // ->add('gender')
            ->add('customer_group', EntityType::class, [
                'label' => 'Groupe de client',
                'class' => MgCustomersGroups::class,
                'choice_label' => 'group_name',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCustomers::class,
        ]);
    }
}
