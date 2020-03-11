<?php

namespace App\Form;

use App\Entity\MgOrdersStatus;
use App\Entity\MgOrdersStatusLang;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('date_add')
            //->add('status_order')
            ->add('status', EntityType::class, [
                'label' => 'Nouvel état',
                'class' => MgOrdersStatusLang::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('os')
                    ->where('os.lang = 1');
                },
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgOrdersStatus::class,
        ]);
    }
}
