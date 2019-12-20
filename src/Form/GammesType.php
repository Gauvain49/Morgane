<?php

namespace App\Form;

use App\Entity\MgGammes;
use App\Form\GammesLangType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GammesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gammesLangs', CollectionType::class, [
                'label_attr' => [
                    'style' => 'display: none;'
                ],
                'entry_type' => GammesLangType::class,
                'allow_add' => true
            ])
            ->add('active', CheckboxType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgGammes::class,
        ]);
    }
}
