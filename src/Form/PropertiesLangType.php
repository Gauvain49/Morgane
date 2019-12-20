<?php

namespace App\Form;

use App\Entity\MgPropertiesLang;
use App\Form\DataTransformer\LanguageToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertiesLangType extends AbstractType
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
                'label' => 'Nom'
                ]
            )
            //->add('url')
            //->add('property')
            ->add('lang', HiddenType::class, [])
            ->get('lang')
                ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgPropertiesLang::class,
        ]);
    }
}
