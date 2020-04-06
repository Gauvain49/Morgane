<?php

namespace App\Controller;

use App\Entity\MgCustomers;
use App\Entity\MgCustomersAddresses;
use App\Entity\MgUsers;
use App\Entity\PasswordUpdate;
use App\Form\CustomerAddressType;
use App\Form\CustomerProfilUserBirthdayType;
use App\Form\CustomerProfilUserType;
use App\Form\CustomerUserType;
use App\Form\CustomersAddressesCompleteType;
use App\Form\CustomersAddressesType;
use App\Form\CustomersType;
use App\Form\PasswordUpdateType;
use App\Repository\MgCountriesRepository;
use App\Repository\MgCustomersAddressesRepository;
use App\Repository\MgCustomersRepository;
use App\Repository\MgGendersRepository;
use App\Repository\MgOrdersRepository;
use App\Repository\MgUsersRepository;
use App\Services\AppService;
use App\Services\SendEmails;
use App\Services\TokenUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
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
    public function new(SessionInterface $session, Request $request, UserPasswordEncoderInterface $encoder, TokenUtils $tokenUtils, MgUsersRepository $repoUser)
    {
    	$user = new MgUsers();
    	$customer = new MgCustomers();
    	$addresses = new MgCustomersAddresses();
        $user->setUsername($session->get('new_inscript'));
        $form = $this->createForm(CustomerUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$task = $form->getData();
            //Calcul de la date à laquelle le client est censé être majeur
            $dateMajorite = new \DateTime();
            $dateMajorite->sub(new \DateInterval('P18Y'));
            $dateBirthday = $task->getCustomers()->getBirthday();
            if ($dateBirthday <= $dateMajorite) {
                $majorite = true;
            } else {
                $majorite = false;
            }
            if (!$majorite) {
                $this->addFlash(
                        'danger',
                        'Votre date de naissance indique que vous n\'êtes pas majeur !');
                $form->get('customers')->get('birthday')->addError(new FormError(" : Vous devez être majeur pour pouvoir vous inscrire sur notre site !"));
            } else {
                $em = $this->getDoctrine()->getManager();
                if ($task->getUsername() != $session->get('new_inscript')) {
                    $checkEmail = $repoUser->findBy(['email' => $task->getUsername()]);
                    if (empty($checkEmail)) {
                        $user->setUsername($task->getUsername());
                        $next = true;
                    } else {
                        $form->get('username')->addError(new FormError("Le nouvel email renseigné est déjà utilisé !"));
                        $this->addFlash(
                                'danger',
                                'Le nouvel email renseigné est déjà utilisé !');
                        $next = false;
                    }
                } else {
                    $next = true;
                }

                if ($next) {
                    $encoded = $encoder->encodePassword($user, $user->getPassword());
                    $user->setPassword($encoded);
                    $user->setEmail($task->getUsername());
                    $user->setRoles(['ROLE_VISITOR']);
                    $user->setActive(true);
                    $user->setToken($tokenUtils->generateToken(60));
                    if (empty($task->getCustomers()->getAddresses()[0]->getPhone()) && empty($task->getCustomers()->getAddresses()[0]->getMobile())) {
                        $form->get('customers')->get('addresses')[0]->get('phone')->addError(new FormError("Vous devez renseignez au moins un téléphone valide."));
                        $form->get('customers')->get('addresses')[0]->get('mobile')->addError(new FormError("Vous devez renseignez au moins un téléphone valide."));
                    } else {
                        $em->persist($user);
                        foreach ($task->getCustomers()->getAddresses() as $address) {
                            $address->setGender($task->getGender());
                            $address->setAddressLastname($task->getLastname());
                            $address->setAddressFirstname($task->getFirstname());
                            $address->setCustomer($user->getCustomers());
                            $address->setTypeAddress(0);
                            $address->setNameAddress('Facturation');
                            $em->persist($address);
                        }
                        $em->flush();
                        if ($request->get('sameAddress') ==  'on') {
                            $session->set('id_user', $user->getId());
                            return $this->redirectToRoute('customer-register-add-address');
                        } else {
                            $this->addFlash(
                                'success',
                                'Vous pouvez maintenant vous connecter en toute sécurité !');
                            return $this->redirectToRoute('account_login');
                        }
                    }
                }
            }

        }

        return $this->render('main/register/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/customer/account/new-address", name="customer-register-add-address")
     */
    public function newAddress(Request $request, MgCustomersAddressesRepository $repoAddress, MgCustomersRepository $repoCustomer, SessionInterface $session, MgUsersRepository $repoUser)
    {
        $user = $repoUser->findOneBy(['id' => $session->get('id_user')]);
        $customer = $repoCustomer->findOneBy(['user' => $user]);
        $addresses = new MgCustomersAddresses();
        $addresses->setAddressLastname($user->getLastname());
        $addresses->setAddressFirstname($user->getFirstname());
        $addresses->setGender($user->getGender());
        $form = $this->createForm(CustomersAddressesType::class, $addresses, ['action' => 'new-address']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $addresses->setCustomer($customer);
            $addresses->setTypeAddress('1');
            $addresses->setNameAddress('Livraison');
            $em = $this->getDoctrine()->getManager();
            $em->persist($addresses);
            $em->flush();
            $session->remove('id_user');

            $this->addFlash(
                'success',
                'Vous pouvez maintenant vous connecter en toute sécurité !');
            return $this->redirectToRoute('account_login');
        }

        return $this->render('main/register/address/new-address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/profil", name="customer_profil", methods="GET|POST")
     * @IsGranted("ROLE_VISITOR", message="Vous n'avez pas les autorisations pour créer des comptes.")
     * Security("is_granted('ROLE_VISITOR') and user === user.getId()", message="Vous n'avez pas accès à cette partie du site.")
     */
    public function index(Request $request, MgUsersRepository $repoUser): Response
    {
        $user = $repoUser->find($this->getUser());
        $form = $this->createForm(CustomerProfilUserBirthdayType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            //Calcul de la date à laquelle le client est censé être majeur
            $dateMajorite = new \DateTime();
            $dateMajorite->sub(new \DateInterval('P18Y'));
            $dateBirthday = $task->getCustomers()->getBirthday();
            if ($dateBirthday <= $dateMajorite) {
                $majorite = true;
            } else {
                $majorite = false;
            }
            if (!$majorite) {
                $this->addFlash(
                        'danger',
                        'Votre date de naissance indique que vous n\'êtes pas majeur !');
                $form->get('customers')->get('birthday')->addError(new FormError(" : Vous devez être majeur pour pouvoir vous inscrire sur notre site !"));
            } else {
                $em = $this->getDoctrine()->getManager();
                $user->setEmail($form->getData()->getUsername());
                $em->persist($user);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Modification réussie !');

                return $this->redirectToRoute('customer_profil');
            }
        }
        return $this->render('main/account/index.html.twig', [
            'customer' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Gestion du mot de passe par le visiteur
     * @Route("/account/profil/password", name="customer_profil_password", methods="GET|POST")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, MgUsersRepository $repoUser)
    {
        $passwordUpdate = new PasswordUpdate();
        //Récupère simplement les infos de l'utilisateur actuellement connecté
        $user = $repoUser->find($this->getUser());
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //1. Vérifier que l'ancien mot de passe renseigné est correcte
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                //Gestion de l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe saisie n'est pas votre mot de passe actuel"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $password = $encoder->encodePassword($user, $newPassword);

                $user->SetPassword($password);

                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Le mot passe a bien été modifié !'
                );

                return $this->redirectToRoute('customer_profil');
            }
        }

        return $this->render('main/account/password/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/profil/addresses", name="customer_profil_addresses", methods="GET|POST")
     * @IsGranted("ROLE_VISITOR", message="Vous n'avez pas les autorisations pour créer des comptes.")
     * Security("is_granted('ROLE_VISITOR') and user === user.getId()", message="Vous n'avez pas accès à cette partie du site.")
     */
    public function profil_address(MgCustomersAddressesRepository $repoAddress, MgGendersRepository $repoGender, MgCountriesRepository $repoCountry): Response
    {
        $customerAddresses = $repoAddress->findByCustomer($this->getUser()->getCustomers());
        $gender = [];
        $country = [];
        foreach ($customerAddresses as $value) {
            $gender[$value->getId()] = $repoGender->findOneBy(['id' => $value->getGender()]);
            $country[$value->getId()] = $repoCountry->findOneBy(['id' => $value->getCountry()]);
        }
        return $this->render('main/account/addresses/index.html.twig', [
            'addresses' => $customerAddresses,
            'gender' => $gender,
            'country' => $country
        ]);
    }

    /**
     * @Route("account/profil/update-address/{type}", name="customer_profil_update_address", methods="GET|POST")
     * @Route("account/update-address", name="customer_profil_new_address", methods="GET|POST")
     */
    public function updateAddress($type = false, Request $request, SessionInterface $session, MgUsersRepository $repoUsers, MgCustomersAddressesRepository $repoAddresses)
    {
        if (!empty($request->attributes->get('type'))) {
            $type = $request->attributes->get('type');
        } else {
            $type = null;
        }
        
        /**
         * Si le visiteur n'a pas d'adresse de définie, on peut demander s'il voudra créer
         * une adresse de livraison différente
         * S'il en a une, inutile de lui demander car l'adresse existante est obligatoirement
         * celle de facturation
         */
        $addresses = $repoAddresses->findByCustomer($this->getUser()->getCustomers());
        if (count($addresses) == 0) {
            $slug = false;
        } else {
            $slug = true;
        }
        // Si le visiteur a des adresses de définies
        if (!is_null($type)) {
            if ($type == 'billing') {
                $customerAddress = $repoAddresses->findOneBy(['customer' => $this->getUser()->getCustomers(), 'type_address' => 0]);
            } elseif($type == 'shipping') {
                $customerAddress = $repoAddresses->findOneBy(['customer' => $this->getUser()->getCustomers(), 'type_address' => 1]);
            } else {
                $this->addFlash(
                    'danger',
                    'Donnée invalide pour modifier l\'adresse');
                return $this->redirectToRoute('customer_profil_addresses');
            }
            $form = $this->createForm(CustomersAddressesCompleteType::class, $customerAddress);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($customerAddress);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Modification réussie !');
                return $this->redirectToRoute('customer_profil_addresses');
            }
        } else {//S'il n'en a qu'une et souhaite en rajouter
            $customerAddress = new MgCustomersAddresses();
            $customerAddress->setAddressFirstname($this->getUser()->getFirstname());
            $customerAddress->setAddressLastname($this->getUser()->getLastname());
            $customerAddress->setGender($this->getUser()->getGender());
            $form = $this->createForm(CustomersAddressesCompleteType::class, $customerAddress);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $customerAddress->setCustomer($this->getUser()->getCustomers());
                if (count($addresses) == 1) {
                    $customerAddress->setTypeAddress(1);
                } else {
                    $customerAddress->setTypeAddress(0);
                }
                
                $em->persist($customerAddress);
                $em->flush();
                if ($request->get('sameAddress')) {
                    return $this->redirectToRoute('customer_new_address');
                }
                $this->addFlash(
                    'success',
                    'Adresse enregistrée !');
                return $this->redirectToRoute('customer_profil_addresses');
            }
        }
        return $this->render('main/account/addresses/update.html.twig', [
            'form' => $form->createView(),
            'slug' => $slug
        ]);
    }

    /**
     * @Route("account/profil/address/{id}", name="customer_address_delete", methods="DELETE")
     */
    public function delete(Request $request, MgCustomersAddresses $address, MgCustomersAddressesRepository $repoAddress): Response
    {
        //On récupère le type pour savoir si c'est l'adresse de facturation et livraison
        $type = $address->getTypeAddress();
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($address);
            $em->flush();
            //Si c'était l'adresse de facturation, il faut convertir celle de livraison
            $address = $repoAddress->findOneBy(['customer' => $this->getUser()->getCustomers()]);
            $address->setTypeAddress(0);
            $em->persist($address);
            $em->flush();

            $this->addFlash(
                'success',
                'Adresse supprimée !');
            return $this->redirectToRoute('customer_profil_addresses');
        }
    }

    /**
     * @Route("/account/profil/orders", name="customer_profil_orders")
     */
    public function getOrdersCustomer(MgOrdersRepository $repoOrders)
    {
        $getOrders = $repoOrders->findByUser($this->getUser());

        return $this->render('main/account/orders/index.html.twig', [
            'orders' => $getOrders
        ]);
    }

    /**
     * @Route("/customer/forgotPassword", name="customer_forgot_password")
     */
    public function forgotPassword(Request $request, MgUsersRepository $repoUsers, UserPasswordEncoderInterface $encoder, TokenUtils $tokenUtils, SendEmails $sendEmails, AppService $appService)
    {
        $form = $this->createFormBuilder()
                ->add('email', EmailType::class, [
                    'label' => 'Votre adresse email',
                    'help' => 'Saisissez l\'adresse e-mail associée à votre compte.'
                ])
                ->add('send', SubmitType::class, [
                    'label' => 'Envoyer'
                ])
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $user = $repoUsers->findOneBy(['email' => $task['email']]);
            if (!empty($user)) {
                $newPassword = $tokenUtils->generateToken(10);
                $encoded = $encoder->encodePassword($user, $newPassword);
                $em = $this->getDoctrine()->getManager();
                $user->setPassword($encoded);
                $em->persist($user);
                $em->flush();

                //Envoi de l'email au client
                $template = $this->forgotPasswordSendNewEmail($user, $newPassword);
                $templateTxt = $this->forgotPasswordSendNewEmailtxt($user, $newPassword);
                //On ne récupère que le content du template, sinon il affiche les headers dans l'en-tête du mail
                $template = $template->getContent();
                $templateTxt = $templateTxt->getContent();

                //Envoi au client
                //dd($user()->getEmail());
                $sendMailCustomer = $sendEmails->sendMailForgotPassword($template, $templateTxt, $appService->getParams()->getEmailContact(), $appService->getParams()->getTitle(), $user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Réinitialisation de votre mot de passe');

                return $this->redirectToRoute('customer_forgot_password_change');

            } else {
                $this->addFlash(
                        'danger',
                        'Utilisateur inconnu !');
                $form->get('email')->addError(new FormError(" : Cet email est inconnu de notre base de données."));
            }
        }
        
        return $this->render('main/forgotPassword/forgotPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/forgotPassword/{user}/{$newPassword}", name="send_new_password")
     */
    public function forgotPasswordSendNewEmail($user, $newPassword)
    {

        $template =  $this->renderView('main/forgotPassword/emails/give_newpassword.html.twig', [
            'user' => $user,
            'newPassword' => $newPassword,
        ]);

        return new Response($template);
    }

    /**
     * @Route("/forgotPassword/{user}/{$newPassword}", name="send_email_confirm_order")
     */
    public function forgotPasswordSendNewEmailtxt($user, $newPassword)
    {

        $template =  $this->renderView('main/forgotPassword/emails/give_newpassword.txt.twig', [
            'user' => $user,
            'newPassword' => $newPassword,
        ]);

        return new Response($template);
    }

    /**
     * @Route("/customer/forgotPasswordChange", name="customer_forgot_password_change")
     */
    public function forgetPasswordchange()
    {
        return $this->render('main/forgotPassword/forgotPasswordChange.html.twig');
    }
}