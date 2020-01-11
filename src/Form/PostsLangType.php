<?php

namespace App\Form;

use App\Entity\MgPostsLang;
use App\Form\DataTransformer\LanguageToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostsLangType extends AbstractType
{
    private $transformer;

    public function __construct(LanguageToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Saisissez votre titre'
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'required' => false]
            )
            //->add('slug')
            //->add('date_up')
            //->add('revision')
            //->add('post')
            ->add('lang', HiddenType::class, [])
            //->add('reviser')
            ->get('lang')
                ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgPostsLang::class,
        ]);
    }
}
