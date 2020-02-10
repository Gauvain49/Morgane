<?php

namespace App\Controller\Admin;

use App\Entity\MgCustomersGroups;
use App\Form\CustomersGroupType;
use App\Repository\MgCustomersGroupsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomersGroupsController extends AbstractController
{
	/**
	 * @Route("admin/customers/groups", name="customers_group")
	 */
	public function index(MgCustomersGroupsRepository $repoGroups)
	{
        return $this->render('admin/customers/groups/index.html.twig', [
            'groups' => $repoGroups->findAll(),
            'NavCustomerOpen' => true
        ]);
	}

    /**
     * @Route("admin/customers/groups/new", name="customers_group_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
    	$customersGroup = new MgCustomersGroups();
        $form = $this->createForm(CustomersGroupType::class, $customersGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customersGroup);
            $em->flush();
            $this->addFlash(
                'Création',
                "Modification réussie !"
            );

            return $this->redirectToRoute('customers_group');
        }

        return $this->render('admin/customers/groups/new.html.twig', [
            'customers_group' => $customersGroup,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/customers/groups/{id}/edit", name="customers_group_edit", methods="GET|POST")
     */
    public function edit(Request $request, MgCustomersGroups $customersGroup): Response
    {
        $form = $this->createForm(CustomersGroupType::class, $customersGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                "Modification réussie !"
            );

            return $this->redirectToRoute('customers_group');
        }

        return $this->render('admin/customers/groups/edit.html.twig', [
            'customers_group' => $customersGroup,
            'form' => $form->createView(),
        ]);
    }
}
