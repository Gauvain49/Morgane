<?php

namespace App\Form;

use App\Entity\MgProperties;
use App\Entity\MgPropertiesContents;
use App\Form\DataTransformer\LanguageToNumberTransformer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertiesValuesType extends AbstractType
{
    private $transformer;

    public function __construct(LanguageToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
            ->add('property', EntityType::class,
                [
                'label' => 'Propriété',
                'class' => MgProperties::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                                ->join('p.properties', 'l')
                                ->addSelect('l')
                                ->where('l.lang = 1');
                },
                'choice_label' => 'properties[0].name'
            ])
            ->add('lang', HiddenType::class, [])
            //->add('product')
            ->get('lang')
                ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgPropertiesContents::class,
        ]);
    }
}
