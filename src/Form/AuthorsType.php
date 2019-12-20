<?php

namespace App\Form;

use App\Entity\MgAuthors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname', TextType::class, [
                'label' => 'Nom'])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => false])
            ->add('bio', TextareaType::class, [
                'label' => 'Biographie',
                'required' => false])
            ->add('email', EmailType::class, [])
            ->add('img', FileType::class, [
                'label' => 'Image/Portrait',
                'required' => false,
                'data_class' => null,
                'attr' => ['placeholder' => 'Choisissez votre fichier', 'lang' => 'fr']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgAuthors::class,
        ]);
    }
}
