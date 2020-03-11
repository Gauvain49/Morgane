<?php

namespace App\Form;

use App\Entity\MgCarriers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarriersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('carrier_name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('carrier_active', CheckboxType::class, [
                'label' => 'Afficher',
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
            /*->add('carrier_default', CheckboxType::class, [
                'label' => 'Transporteur par défaut',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger',
                    'data-style' => 'btn-round',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ],
                'data' => false,
                'required' => false
            ])*/
            ->add('carriersLang', CollectionType::class, [
                'label_attr' => [
                    'style' => 'display: none;'
                ],
                'entry_type' => CarriersLangType::class,
                'allow_add' => true
            ])
            ->add('carrier_logo', FileType::class, [
                'label' => 'Logo',
                'required' => false,
                'data_class' => null,
                'attr' => ['placeholder' => 'Choisissez votre fichier', 'lang' => 'fr']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCarriers::class,
        ]);
    }
}
