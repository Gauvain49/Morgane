<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'attr' => [
                    'style' => 'max-width: 500px;'
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Vous n\'avez pas saisie le même mot de passe.',
                'required' => true,
                'first_options' => ['label' => 'Nouveau mot de passe', 'attr' => ['style' => 'max-width: 500px;'], 'help' => '8 caractères minimum, contenant au moins une majuscule et un chiffre.'],
                'second_options' => ['label' => 'Confirmez votre nouveau mot de passe', 'invalid_message' => 'Vous n\'avez pas saisie le même mot de passe.', 'attr' => ['style' => 'max-width: 500px;']]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
