<?php

namespace App\Form;

use App\Entity\MgCategories;
use App\Entity\MgGammes;
use App\Entity\MgProducts;
use App\Entity\MgSuppliers;
use App\Entity\MgTaxes;
use App\Form\DataTransformer\CategorieToNumberTransformer;
use App\Form\ProductsLangType;
use App\Repository\MgCategoriesRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
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
                'entry_type' => ProductsLangType::class,
                'allow_add' => true
            ])
            ->add('reference', TextType::class, [
                'label' => 'Référence',
                'required' => false
            ])
            ->add('purshasing_price', MoneyType::class, [
                'label' => 'Prix d\'achat',
                'required' => false
            ])
            ->add('selling_price', MoneyType::class, [
                'label' => 'Prix de vente HT',
            ])
            ->add('selling_price_all_taxes', MoneyType::class, [
                'label' => 'Prix de vente TTC',
            ])
            ->add('sales_unit', IntegerType::class, [
                'label' => 'Unité de vente',
                'attr' => [
                    'placeholder' => 1
                ],
                'data' => 1
            ])
            /*->add('categories', EntityType::class, [
                'class' => MgCategories::class,
                'query_builder' => function(MgCategoriesRepository $repo) {
                    return $repo->findAllByArborescence('product');
                }*/
            ->add('categories', ChoiceType::class, [
                'choices' => $options['checkbox'],
                'choice_value' => function($categories) {
                    if(!is_integer($categories)) {
                        return $categories->getId();
                    }
                },
                'choice_label' => 'contents[0].name',
                'choice_attr' => function($categories, $key, $value) {
                    if ($categories->getLevel() > 0) {
                        return ['style' => [
                                    'padding-left' => "{$categories->getLevel()}5px"
                                ]
                            ];
                    }
                    return [];
                },
                'expanded' => true,
                'multiple' => true,
                'required' => false
            ])
            ->add('min_quantity', IntegerType::class, [
                'label' => 'Minimum commandable',
                'attr' => [
                    'placeholder' => 1
                ],
                'data' => 1
            ])
            ->add('max_quantity', IntegerType::class, [
                'label' => 'Maximum commandable',
                'required' => false
            ])
            ->add('bulk_quantity', IntegerType::class, [
                'label' => 'Commande en vrac de',
                'required' => false
            ])
            ->add('stock_management', CheckboxType::class, [
                'label' => 'Gérer les stocks',
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
            ->add('pre_order', CheckboxType::class, [
                'label' => 'Précommande autorisée',
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
            ->add('available_date', DateTimeType::class, [
                'label' => 'Date de disponibilité',
                'format' => 'dd-MM-yyyy',
                'view_timezone' => 'Europe/Paris',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
                'help' => 'Date à laquelle le produit est disponible à la vente.',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'html5' => true
            ])
            ->add('date_publish', DateTimeType::class, [
                'label' => 'Date de publication',
                'format' => 'dd-MM-yyyy',
                'view_timezone' => 'Europe/Paris',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
                'help' => 'Date à laquelle le produit apparaît dans le catalogue.',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'html5' => true
            ])
            ->add('offline', CheckboxType::class, [
                'label' => 'Mettre hors ligne',
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
            ->add('taxe', EntityType::class, [
                'class' => MgTaxes::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.taxe_rate', 'DESC');
                },
                'choice_label' => 'taxe_rate',
                'choice_attr' => function($choiceValue, $key, $value) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['data-role' => $choiceValue->getTaxeRate()];
                }
            ])
            ->add('supplier', EntityType::class, [
                'label' => 'Fournisseur',
                'class' => MgSuppliers::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.supplier_name', 'DESC');
                },
                'choice_label' => 'supplier_name'
            ])
            ->add('gamme', EntityType::class, [
                'label' => 'Gamme',
                'class' => MgGammes::class,
                'choice_label' => 'gamme_active'
            ])
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
                                //dump($value->getId());
                                $truc[] = $value->getId();
                            }
                            return $truc;
                        }
                        
                        //return $categoriesAsString;
                    },
                    function ($categoriesAsArray) {
                        //dump($categoriesAsArray);
                        // transforme le tableau en string
                        //return implode(', ', $categoriesAsArray);
                        return $categoriesAsArray;
                    }
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgProducts::class,
        ])
            ->setRequired(
            'checkbox'
        );
    }
}