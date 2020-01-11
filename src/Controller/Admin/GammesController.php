<?php

namespace App\Controller\Admin;

use App\Entity\MgGammes;
use App\Entity\MgGammesLang;
use App\Form\GammesType;
use App\Repository\MgGammesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/gammes", name="gammes_")
 */
class GammesController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(MgGammesRepository $GammesRepository): Response
    {
        return $this->render('admin/gammes/index.html.twig', [
            'gammes' => $GammesRepository->findAll(),
            'NavCatalogOpen' => true,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gamme = new MgGammes();
        $gammeLang = new MgGammesLang();
        $form = $this->createForm(GammesType::class, $gamme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($gamme->getGammesLangs() as $gammeLang) {
                $gammeLang->setGamme($gamme);
                $entityManager->persist($gammeLang);
            }
            $entityManager->persist($gamme);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "Création réussie !"
            );

            return $this->redirectToRoute('gammes_index');
        }

        return $this->render('admin/gammes/new.html.twig', [
            'gamme' => $gamme,
            'form' => $form->createView(),
            'NavCatalogOpen' => true,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgGammes $gamme): Response
    {
        $form = $this->createForm(GammesType::class, $gamme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                "Modification réussie !"
            );

            return $this->redirectToRoute('gammes_index');
        }

        return $this->render('admin/gammes/edit.html.twig', [
            'gamme' => $gamme,
            'form' => $form->createView(),
            'NavCatalogOpen' => true,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgGammes $gamme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gamme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gamme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gammes_index');
    }
}
