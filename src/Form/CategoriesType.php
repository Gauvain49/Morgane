<?php

namespace App\Form;

use App\Entity\MgCategories;
use App\Form\CategoriesLangType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contents', CollectionType::class, [
                'label_attr' => [
                    'style' => 'display: none;'
                ],
                'entry_type' => CategoriesLangType::class,
                'allow_add' => true
            ])
            ->add('parent', ChoiceType::class, [
                'choices' => $options['checkbox'],
                'choice_value' => function($categories) {
                    if(!is_null($categories)) {
                        return $categories->getId();
                    }
                },
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('position', IntegerType::class, [
                'required' => false
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
            ->add('force_display', CheckboxType::class, [
                'label' => 'Forcer l\'affichage si vide',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger',
                    'data-style' => 'btn-round',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCategories::class,
        ])
                ->setRequired('checkbox');
    }
}
