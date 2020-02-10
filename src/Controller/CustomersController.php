<?php

namespace App\Controller;

use App\Entity\MgCustomers;
use App\Entity\MgCustomersAddresses;
use App\Entity\MgUsers;
use App\Form\CustomerUserType;
use App\Services\TokenUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CustomersController extends AbstractController
{
    /**
     * @Route("/customer/account/new", name="customer-register")
     */
    public function new(SessionInterface $session, Request $request, UserPasswordEncoderInterface $encoder, TokenUtils $tokenUtils)
    {
    	$user = new MgUsers();
    	$customer = new MgCustomers();
    	$addresses = new MgCustomersAddresses();
        $user->setUsername($session->get('new_inscript'));
        $form = $this->createForm(CustomerUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$task = $form->getData();
        	//dd($request->get('sameAddress'));
            $em = $this->getDoctrine()->getManager();
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $user->setEmail($task->getUsername());
            $user->setRoles(['ROLE_VISITOR']);
            $user->setActive(true);
            $user->setToken($tokenUtils->generateToken(60));
            //$customer->setUser($user);
            //$customer->setCompagny($task->getCustomers()->getCompagny());
            //$customer->setBirthday($task->getCustomers()->getBirthday());
            $em->persist($user);
            //$em->persist($customer);
            foreach ($task->getCustomers()->getAddresses() as $address) {
            	$address->setGender($task->getGender());
            	$address->setAddressLastname($task->getLastname());
            	$address->setAddressFirstname($task->getFirstname());
            	$address->setCustomer($user->getCustomers());
            	$address->setTypeAddress(0);
            	$em->persist($address);
            }
            $em->flush();
            //$request->get('sameAddress');

            $this->addFlash(
                'success',
                'Vous pouvez maintenant vous connecter en toute sécurité !');
            return $this->redirectToRoute('login');
        }

        return $this->render('main/register/new.html.twig', [
            //'mg_customer' => $mgCustomer,
            'form' => $form->createView(),
        ]);
    }
}