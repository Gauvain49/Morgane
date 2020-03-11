<?php

namespace App\Form;

use App\Entity\MgCountries;
use App\Entity\MgCustomersAddresses;
use App\Entity\MgGenders;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomersAddressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['action'] == 'new-address') {
            $builder
                ->add('address_lastname', TextType::class, [
                    'label' => 'Votre nom'
                ])
                ->add('address_firstname', TextType::class, [
                    'label' => 'Votre prénom'
                ])
                ->add('gender', EntityType::class, [
                    'label' => false,
                    'class' => MgGenders::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                        ->where('g.lang = 1');
                    },
                    'choice_label' => 'short_gender',
                    'expanded' => true,
                    'multiple' => false,
                    'required' => true,
                    'choice_attr' => function($choice, $key, $value) {
                        // adds a class like attending_yes, attending_no, etc
                        if ($key == 0) {
                            return ['checked' => 'checked'];
                        } else {
                            return [];
                        }
                    }
                ])
                ->add('address_compagny', TextType::class, [
                    'label' => 'Societé',
                    'attr' => [
                        'style' => 'max-width: 500px'
                    ],
                    'required' => false])
                ;
        }
        $builder
            ->add('country', EntityType::class, [
                'label' => 'Votre Pays',
                'class' => MgCountries::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                            ->join('c.countriesLangs', 'l')
                            ->addSelect('l')
                            ->where('l.lang = 1')
                            ->orderBy('l.country_name', 'ASC');
                },
                'choice_label' => 'countriesLangs[0].country_name',
                'choice_attr' => function ($choice, $key, $value) {
                    //dump($choice->getZipCodeFormat());
                    if ($value == '8') {
                        return ['selected' => 'selected', 'data-zipformat' => $choice->getZipCodeFormat()];
                    } else {
                        return ['data-zipformat' => $choice->getZipCodeFormat()];
                    }
                }
            ])
            //->add('address_compagny')
            ->add('address', TextareaType::class, [
                'label' => 'Votre adresse'
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Votre code postal'
            ])
            ->add('town', TextType::class, [
                'label' => 'Votre ville'
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre téléphone fixe',
                'attr' => [
                    'style' => 'max-width: 200px'
                ],
                'required' => false,
                'help' => 'Veuillez fournir au moins un téléphone valide. Il servira exclusivement à vous contacter, en cas de besoin, pour faciliter les livraisons.'
            ])
            ->add('mobile', TelType::class, [
                'label' => 'Votre téléphone mobile',
                'attr' => [
                    'style' => 'max-width: 200px'
                ],
                'required' => false,
            ])
            //->add('type_address')
            //->add('name_address')
            //->add('customer')
            //->add('gender')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgCustomersAddresses::class,
        ]);
    }
}
