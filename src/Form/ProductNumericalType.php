<?php

namespace App\Form;

use App\Entity\MgProductsNumerical;
use App\Entity\MgTaxes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductNumericalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('exclusive', CheckboxType::class, [
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
            ->add('nb_days_accessibles', IntegerType::class, [
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgProductsNumerical::class,
        ]);
    }
}
