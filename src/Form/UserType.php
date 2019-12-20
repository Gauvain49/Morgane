<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('username', TextType::class, [
				'label' => 'Identifiant',
				'help' => 'Il est possible de choisir un identifiant libre contenant au moins 6 caractères, mais il est conseillé de saisir votre adresse email',
				'attr' => [
					'style' => 'max-width: 500px'
				]
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
			->add('email', EmailType::class, [
				'label' => 'E-mail',
				'attr' => [
					'style' => 'max-width: 500px'
				]
			])
			->add('password', RepeatedType::class,[
				'type' => PasswordType::class,
				'invalid_message' => 'Vous n\'avez pas saisie le même mot de passe.',
				'required' => true,
				'first_options' => ['label' => 'Mot de passe', 'attr' => ['style' => 'max-width: 500px'], 'help' => '8 caractères minimum, contenant au moins une majuscule et un chiffre.'],
				'second_options' => ['label' => 'Confirmez votre mot de passe', 'invalid_message' => 'Vous n\'avez pas saisie le même mot de passe.', 'attr' => ['style' => 'max-width: 500px']]
			])
			->add('roles', ChoiceType::class, [
				'choices' => array(
					'Super administrateur' => 'ROLE_SUPER_ADMIN',
					'Administrateur' => 'ROLE_ADMIN',
					'Utilisateur' => 'ROLE_USER',
					'Contributeur' => 'ROLE_CONTR'
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
			]);
	}
}