<?php

namespace App\Form;

use App\Entity\MgPosts;
use App\Form\DataTransformer\CategorieToNumberTransformer;
use App\Form\PostsLangType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostsType extends AbstractType
{
    private $transformer;

    public function __construct(CategorieToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contents', CollectionType::class, [
                'label_attr' => [
                    'style' => 'display: none;'
                ],
                'entry_type' => PostsLangType::class,
                'allow_add' => true
                ]
            )
        ;
        if ($options['action'] == 'page' || $options['data']->getType() == 'page') {
            $builder
                ->add('parent', ChoiceType::class, [
                    'label' => 'Page parente',
                    'choices' => $options['checkbox'],
                    'choice_value' => function($categories) {
                        if(!is_null($categories)) {
                            return $categories->getId();
                        }
                    },
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false
                    ]
                )
            ;
        } else {
            $builder
               ->add('categories', ChoiceType::class, [
                    'choices' => $options['checkbox'],
                    'choice_value' => function($categories) {
                        if(!is_integer($categories)) {
                            return $categories->getId();
                        }
                    },
                    'multiple' => true,
                    'expanded' => true,
                    'help' => 'Si vous ne choisissez aucune catégorie, c\'est la catégorie \'Non classé\' qui sera attribuée par défault',
                ]
            )
            ->get('categories')
                //->addModelTransformer($this->transformer)
                ->addModelTransformer(new CallbackTransformer(
                    function ($categoriesAsString) {
                        // transforme le retour string en tableau
                        //dump($categoriesAsString);
                        if (is_array($categoriesAsString)) {
                            return explode(', ', $categoriesAsString);
                        } else {
                            $truc = array();
                            foreach ($categoriesAsString as $value) {
                                $truc[] = $value->getId();
                            }
                            return $truc;
                        }
                    },
                    function ($categoriesAsArray) {
                        //dump($categoriesAsArray);
                        // transforme le tableau en string
                        //return implode(', ', $categoriesAsArray);
                        return $categoriesAsArray;
                    }
                ));
        }
        $builder
            ->add('password', TextType::class, [
                'label' => 'Protéger par un mot de passe',
                //'always_empty' => false,
                'required' => false
                ]
            )
            ->add('comment', CheckboxType::class, [
                'label' => 'Autoriser les commentaires',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger',
                    'data-style' => 'btn-round',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ],
                'required' => false
                ]
            )
            //->add('filename')
            //->add('reserved')
            //->add('date_add')
            ->add('date_publish', DateTimeType::class, [
                'label' => 'Date de publication',
                'format' => 'dd-MM-yyyy',
                'view_timezone' => 'Europe/Paris',
                'help' => 'Laisser les champs vides s\'il n\'y a pas de date programmée.',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false
                ]
            )
            ->add('date_expire', DateTimeType::class, [
                'label' => 'Date d\'expiration',
                'format' => 'dd-MM-yyyy',
                'view_timezone' => 'Europe/Paris',
                'help' => 'Laisser les champs vides s\'il n\'y a pas de date programmée.',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false
                ]
            )
            ->add('post_draft', SubmitType::class, [
                'label' => 'Enregistrer comme brouillon',
                'attr' => ['class' => 'btn btn-primary btn-border'
                ]
            ])
            ->add('post_view', SubmitType::class, [
                'label' => 'Visualiser',
                'attr' => ['class' => 'btn btn-warning'
                ]
            ])
            ->add('post_publish', SubmitType::class, [
                'label' => 'Publier',
                'attr' => ['class' => 'btn btn-primary'
                ]
            ])
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgPosts::class,
        ])
                ->setRequired(
            'checkbox',
            'action'
        );
    }
}
