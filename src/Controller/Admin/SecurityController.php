<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/login/login.html.twig', [
			'last_username' => $lastUsername,
			'hasError' => $error !== null
        ]);
    }

    /**
     * @Route ("/admin/logout", name="admin_logout")
     */
    public function logout()
    {
        //Rien à mettre, symfony prend tout en charge.
    }
}
