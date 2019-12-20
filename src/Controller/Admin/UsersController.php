<?php

namespace App\Controller\Admin;

use App\Entity\MgUsers;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Form\ProfileType;
use App\Form\UserType;
use App\Repository\MgUsersRepository;
use App\Services\TokenUtils;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/users")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users_index", methods={"GET"})
     */
    public function index(MgUsersRepository $users)
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

	/**
	 * @Route("/new", name="users_new", methods={"GET","POST"})
	 * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les autorisations pour créer des comptes.")
	 */
	public function register(Request $request, UserPasswordEncoderInterface $encoder, TokenUtils $tokenUtils)
	{
		$user = new MgUsers();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $this->getDoctrine()->getManager();
			$user->setDateAdd(new \DateTime());
			$user->setActive(true);
			$user->setToken($tokenUtils->generateToken(60));
			$encoded = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($encoded);
			$entityManager->persist($user);
			$entityManager->flush();

			$this->addFlash(
				'success',
				"L'inscription a réussi !"
			);

			return $this->redirectToRoute('users_index', []);
		}
		return $this->render("admin/users/new.html.twig", [
			'form' => $form->createView(),
			'editMode' => $user->getId() !== null,
			'titre' => $user->getId() !== null
		]);
	}

	/**
	 * Permet à un utilisateur de modifier son profil
	 * @Route("/{id}/edit", name="users_profile")
	 * Security("is_granted('ROLE_ADMIN') and user === user.getId()", message="Vous ne pouvez modifier que votre propre compte.")
	 * @return Response
	 */
	public function edit(Request $request, MgUsers $user)
	{
		//$user = $this->getUser();
		$form = $this->createForm(ProfileType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();
			$this->addFlash(
				'success',
				"Votre compte a bien été modifié !"
			);
		}
		return $this->render('admin/users/edit.html.twig', [
			'form' => $form->createView(),
			'user' => $user
		]);
	}

	/**
	 * Permet de modifier le mot de passe de l'utilisateur
	 *
	 * @Route ("/update-password", name="users_password")
	 *
	 * @return Response
	 */
	public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder)
	{
		$passwordUpdate = new PasswordUpdate();
		//Récupère simplement les infos de l'utilisateur actuellement connecté
		$user = $this->getUser();
		$form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $this->getDoctrine()->getManager();
			//1. Vérifier que l'ancien mot de passe renseigné est correcte
			if(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
				//Gestion de l'erreur
				$form->get('oldPassword')->addError(new FormError("Le mot de passe saisie n'est pas votre mot de passe actuel"));
			} else {
				$newPassword = $passwordUpdate->getNewPassword();
				$password = $encoder->encodePassword($user, $newPassword);

				$user->SetPassword($password);

				$entityManager->persist($user);
				$entityManager->flush();

				$this->addFlash(
					'success',
					'Le mot passe a bien été modifié !'
				);

				return $this->redirectToRoute('users_profile', ['id' => $this->getUser()->getId()]);
			}
		}

		return $this->render('admin/users/password-update.html.twig', [
			'form' => $form->createView()
		]);
	}

    /**
     * @Route("/{id}", name="users_delete", methods="DELETE")
     */
    public function delete(Request $request, MgUsers $user)
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            $this->addFlash(
                'success',
                "L'utilisateur a bien été supprimé."
            );
        }

        return $this->redirectToRoute('users_index');
    }
}
