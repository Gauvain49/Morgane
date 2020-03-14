<?php

namespace App\Form;

use App\Entity\MgCarriersAmountDepartments;
use App\Form\DataTransformer\DepartmentToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarriersAmountDepartmentsType extends AbstractType
{
    private $transformer;

    public function __construct(DepartmentToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('department_amount', MoneyType::class, [
            ])
            ->add('step_department', HiddenType::class, [])
            ->get('step_department')
                ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCarriersAmountDepartments::class,
        ]);
    }
}
