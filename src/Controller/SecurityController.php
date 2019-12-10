<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('main/login/login.html.twig', [
			'last_username' => $lastUsername,
			'hasError' => $error !== null
        ]);
    }

    /**
     * @Route ("/logout", name="logout")
     */
    public function logout()
    {
        //Rien à mettre, symfony prend tout en charge.
    }
}
