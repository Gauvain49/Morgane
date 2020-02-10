<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civility', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Monsieur' => 'M.',
                    'Madame' => 'Mme'
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 'M.'
                ])
            ->add('name', TextType::class, [
                'label' => 'Votre nom'])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom'])
            ->add('phone', TextType::class, [
                'label' => 'Votre téléphone'])
            ->add('email', EmailType::class, [
                'label' => 'Votre Email',
                'required' => false])
            ->add('subject', TextType::class, [
                'label' => 'Sujet de votre message'])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
