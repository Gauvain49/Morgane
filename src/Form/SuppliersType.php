<?php

namespace App\Form;

use App\Entity\MgSuppliers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuppliersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier_name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'style' => 'max-width: 500px'
                ]])
            ->add('supplier_address', TextareaType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'style' => 'max-width: 500px'
                ]])
            ->add('supplier_zipcode', TextType::class, [
                'label' => 'Code Postal',
                'attr' => [
                    'style' => 'max-width: 500px'
                ],
                'required' => false])
            ->add('supplier_town', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'style' => 'max-width: 500px'
                ],
                'required' => false])
            ->add('supplier_phone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'style' => 'max-width: 500px'
                ],
                'required' => false])
            ->add('supplier_email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'style' => 'max-width: 500px'
                ],
                'required' => false])
            ->add('supplier_web', UrlType::class, [
                'label' => 'Site web',
                'attr' => [
                    'style' => 'max-width: 500px'
                ],
                'required' => false])
            ->add('supplier_logo', FileType::class, [
                'label' => 'Logo',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'style' => 'max-width: 500px',
                    'placeholder' => 'Choisissez votre fichier',
                    'lang' => 'fr'
                ]
            ])
            ->add('suppliers_notes', TextareaType::class, [
                'label' => 'Information complémentaire',
                'required' => false,
                'attr' => [
                    'style' => 'max-width: 500px'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgSuppliers::class,
        ]);
    }
}
