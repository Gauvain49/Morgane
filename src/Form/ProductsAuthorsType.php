<?php

namespace App\Form;

use App\Entity\MgAuthors;
use App\Entity\MgProducts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsAuthorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authors', EntityType::class, [
                'label' => 'Auteurs',
                'class' => MgAuthors::class,
                'choice_label' => 'completeName',
                'multiple' => true,
                'expanded' => false,
                'help' => 'Saisissez l\'auteur à attribuer. Si le produit possède plusieurs auteurs, vous pouvez les saisir les uns après les autres.'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgProducts::class,
        ]);
    }
}
