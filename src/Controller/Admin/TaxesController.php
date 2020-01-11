<?php

namespace App\Controller\Admin;

use App\Entity\MgTaxes;
use App\Form\TaxesType;
use App\Repository\MgTaxesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/taxes")
 */
class TaxesController extends AbstractController
{
    /**
     * @Route("/", name="taxes_index", methods={"GET"})
     */
    public function index(MgTaxesRepository $mgTaxRepository): Response
    {
        return $this->render('admin/taxes/index.html.twig', [
            'taxes' => $mgTaxRepository->findAll(),
            'NavCatalogOpen' => true,
        ]);
    }

    /**
     * @Route("/new", name="taxes_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $taxe = new MgTaxes();
        $form = $this->createForm(TaxesType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($taxe);
            $entityManager->flush();

            return $this->redirectToRoute('taxes_index');
        }

        return $this->render('admin/taxes/new.html.twig', [
            'taxe' => $taxe,
            'form' => $form->createView(),
            'NavCatalogOpen' => true,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="taxes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgTaxes $taxe): Response
    {
        $form = $this->createForm(TaxesType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mg_taxes_index');
        }

        return $this->render('admin/taxes/edit.html.twig', [
            'taxe' => $taxe,
            'form' => $form->createView(),
            'NavCatalogOpen' => true,
        ]);
    }

    /**
     * @Route("/{id}", name="taxes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgTaxes $taxe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taxe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taxe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('taxes_index');
    }
}
