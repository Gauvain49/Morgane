<?php

namespace App\Form;

use App\Entity\MgCustomersGroups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomersGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group_name', TextType::class, [
                'label' => 'Nom du groupe'])
            ->add('group_discount', NumberType::class, [
                'label' => 'Remise accordée (en pourcentage)',
                'required' => false,
                'help' => 'La remise se calcul sur le montant ttc de chaque article. Si un article possède déjà sa propre remise, c\'est la plus avantageuse pour le client qui est prise en compte',
                'attr' => ['style' => 'max-width: 65px']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCustomersGroups::class,
        ]);
    }
}
