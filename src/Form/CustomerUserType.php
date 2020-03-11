<?php

namespace App\Form;

use App\Entity\MgGenders;
use App\Entity\MgUsers;
use App\Form\CustomersType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerUserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('username', EmailType::class, [
				'label' => 'Identifiant',
				'help' => 'Votre adresse email',
				'attr' => [
					'style' => 'max-width: 500px'
				]
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
			->add('firstname', TextType::class, [
				'label' => 'Prénom',
				'attr' => [
					'style' => 'max-width: 500px'
				]
			])
			->add('lastname', TextType::class, [
				'label' => 'Nom',
				'attr' => [
					'style' => 'max-width: 500px'
				]
			])
			/*->add('email', EmailType::class, [
				'label' => 'E-mail',
				'attr' => [
					'style' => 'max-width: 500px'
				]
			])*/
			->add('password', RepeatedType::class,[
				'type' => PasswordType::class,
				'invalid_message' => 'Vous n\'avez pas saisie le même mot de passe.',
				'required' => true,
				'first_options' => ['label' => 'Mot de passe', 'attr' => ['style' => 'max-width: 500px'], 'help' => '8 caractères minimum, contenant au moins une majuscule et un chiffre.'],
				'second_options' => ['label' => 'Confirmez votre mot de passe', 'invalid_message' => 'Vous n\'avez pas saisie le même mot de passe.', 'attr' => ['style' => 'max-width: 500px']]
			])
			/*->add('roles', ChoiceType::class, [
				'choices' => array(
					'Super administrateur' => 'ROLE_SUPER_ADMIN',
					'Administrateur' => 'ROLE_ADMIN',
					'Utilisateur' => 'ROLE_ADMIN_USER',
					'Contributeur' => 'ROLE_ADMIN_CONTR'
				),
				'multiple' => true,
				'label' => 'Rôle',
				'attr' => [
					'style' => 'max-width: 500px'
				]
			])
			->add('Enregistrer', SubmitType::class, [
				'attr' => [
					'class' => 'btn btn-primary'
				]
			])*/
			->add('customers', CustomersType::class, [
				'label_attr' => [
					'style' => 'display: none;']])
			;
	}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MgUsers::class,
        ]);
    }
}