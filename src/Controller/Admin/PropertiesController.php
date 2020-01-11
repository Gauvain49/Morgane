<?php

namespace App\Controller\Admin;

use App\Entity\MgProperties;
use App\Entity\MgPropertiesLang;
use App\Form\PropertiesType;
use App\Repository\MgPropertiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/properties")
 */
class PropertiesController extends AbstractController
{
    /**
     * @Route("/", name="properties_index", methods={"GET"})
     */
    public function index(MgPropertiesRepository $propertiesRepository): Response
    {
        return $this->render('admin/properties/index.html.twig', [
            'properties' => $propertiesRepository->findAll(),
            'NavCatalogOpen' => true
        ]);
    }

    /**
     * @Route("/new", name="properties_new", methods={"GET","POST"})
     */
    public function new(Request $request, MgPropertiesRepository $repoProperty): Response
    {
        $propertyLang = new MgPropertiesLang();
        $property = new MgProperties();
        $recupPosition = $repoProperty->findOneBy([], ['position' => 'DESC']);
        $position = !empty($recupPosition) ? $recupPosition->getPosition() + 1 : 1;
        $form = $this->createForm(PropertiesType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($property->getProperties() as $propertyLang) {
                $propertyLang->setProperty($property);
                $entityManager->persist($propertyLang);
            }
            $property->setPosition($position);
            $entityManager->persist($property);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "Création réussie !"
            );

            return $this->redirectToRoute('properties_index');
        }

        return $this->render('admin/properties/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'NavCatalogOpen' => true,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="properties_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgProperties $property): Response
    {
        $form = $this->createForm(PropertiesType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($property->getProperties() as $propertyLang) {
                $propertyLang->setProperty($property);
                $entityManager->persist($propertyLang);
            }
            $entityManager->persist($property);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "Modification réussie !"
            );

            return $this->redirectToRoute('properties_index');
        }

        return $this->render('admin/properties/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
            'NavCatalogOpen' => true,
        ]);
    }

    /**
     * @Route("/{id}", name="properties_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgProperties $property): Response
    {
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($property);
            $entityManager->flush();
        }

        return $this->redirectToRoute('properties_index');
    }
}
