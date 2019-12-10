<?php

namespace App\Form;

use App\Entity\MgProductsLang;
use App\Form\DataTransformer\LanguageToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsLangType extends AbstractType
{
    private $transformer;

    public function __construct(LanguageToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                //'label_attr' => ['class' => 'col-form-label'],
                'required' => false,
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Résumé',
                //'label_attr' => ['class' => 'col-form-label'],
                'required' => false,
                'attr' => [
                    'class' => 'editor']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                //'label_attr' => ['class' => 'col-form-label'],
                'required' => false,
                /*'attr' => [
                    'class' => 'col-5']*/
            ])
            //->add('slug')
            //->add('product')
            ->add('lang', HiddenType::class, [])
            ->get('lang')
                ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgProductsLang::class,
        ]);
    }
}
