<?php

namespace App\Controller;

use App\Repository\MgUsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/customer/account/login/check", name="account_check_login", methods="GET|POST")
     */
    public function checkLogin(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $customer = new MgCustomers();
        $error = $authenticationUtils->getLastAuthenticationError();
        //dump($error);
        //exit();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('main/login/login.html.twig', [
            'last_username' => $lastUsername,
            'hasError' => $error !== null,
        ]);
    }
    /**
     * @Route("/customer/account/login", name="account_login", methods="GET|POST")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils, MgUsersRepository $repoUser, SessionInterface $session)
    {
        //$customer = new MgUsers();
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse email'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            //dd($task['email']);
            $checkEmail = $repoUser->findBy(['email' => $task['email']]);
            //dd($checkEmail);
            if (empty($checkEmail)) {
                $session->set('new_inscript', $task['email']);
                return $this->redirectToRoute('customer-register');
            } else {
                $this->addFlash(
                    'notice',
                    'Cet email est déjà utilisé !'
                );
            }
        }
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('main/login/login.html.twig', [
			'last_username' => $lastUsername,
			'hasError' => $error !== null,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/customer/account/logout", name="account_logout")
     */
    public function logout()
    {
        //Rien à mettre, symfony prend tout en charge.
    }
}
