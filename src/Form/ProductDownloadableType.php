<?php

namespace App\Form;

use App\Entity\MgProducts;
use App\Entity\MgTaxes;
use App\Form\ProductNumericalType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductDownloadableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numericals', CollectionType::class, [
                //'label' => 'Contenu',
                'label_attr' => [
                    'style' => 'display: none'
                ],
                'entry_type' => ProductNumericalType::class,
                'allow_add' => false
            ])
            ->add('selling_price', MoneyType::class, [
                'label' => 'Prix de vente HT',
                'label_attr' => [
                    'class' => 'col-form-label'
                ]
            ])
            ->add('taxe', EntityType::class, [
                'class' => MgTaxes::class,
                'label_attr' => [
                    'class' => 'col-form-label'
                ],
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
            ->add('selling_price_all_taxes', MoneyType::class, [
                'label' => 'Prix de vente TTC',
                'label_attr' => [
                    'class' => 'col-form-label'
                ]
            ])
            /*->add('exclusive', CheckboxType::class, [
                'label' => 'Afficher uniquement la version numérique',
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
            ->add('nb_days_accessible', IntegerType::class, [
                'label' => 'Nbre de jour accessible après commande',
                'label_attr' => [
                    'class' => 'col-form-label'
                ],
                'required' => false,
                'help' => 'Laissez le champ vide s\'il n\'y a pas de limite.'
            ])
            ->add('nb_downloadable', IntegerType::class, [
                'label' => 'Nbre de téléchargement possible',
                'label_attr' => [
                    'class' => 'col-form-label'
                ],
                'required' => false,
                'help' => 'Laissez le champ vide s\'il n\'y a pas de limite.'
            ])*/
            ->add('offline', CheckboxType::class, [
                'label' => 'Activer',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-onstyle' => 'danger',
                    'data-offstyle' => 'success',
                    'data-style' => 'btn-round',
                    'data-on' => 'Non',
                    'data-off' => 'Oui'
                ],
                'required' => false
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
