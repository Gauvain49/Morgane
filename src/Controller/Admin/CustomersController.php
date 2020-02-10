<?php

namespace App\Controller\Admin;

use App\Entity\MgCustomers;
use App\Entity\MgUsers;
use App\Form\CustomerUserAdminType;
use App\Form\CustomerUserType;
use App\Form\CustomersType;
use App\Repository\MgCustomersRepository;
use App\Repository\MgUsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/customers", name="customers_")
 */
class CustomersController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(MgUsersRepository $usersRepository, MgCustomersRepository $customersRepository): Response
    {
        $customers = $usersRepository->getUsersByRoles('ROLE_VISITOR');
        return $this->render('admin/customers/index.html.twig', [
            'users' => $customers,
            'NavCustomerOpen' => true
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $customer = new MgCustomers();
        $form = $this->createForm(CustomersType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('customers_index');
        }

        return $this->render('admin/customers/new.html.twig', [
            'mg_customer' => $customer,
            'form' => $form->createView(),
            'NavCustomerOpen' => true
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgUsers $user): Response
    {
        $form = $this->createForm(CustomerUserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customers_index');
        }

        return $this->render('admin/customers/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'NavCustomerOpen' => true
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgCustomers $customer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customers_index');
    }
}
