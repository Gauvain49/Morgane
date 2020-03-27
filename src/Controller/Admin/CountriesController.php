<?php

namespace App\Controller\Admin;

use App\Entity\MgCountries;
use App\Form\CountriesType;
use App\Repository\MgCountriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/countries")
 */
class CountriesController extends AbstractController
{
    /**
     * @Route("/", name="countries_index", methods={"GET"})
     */
    public function index(MgCountriesRepository $countriesRepository): Response
    {
        return $this->render('admin/countries/index.html.twig', [
            'countries' => $countriesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="countries_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgCountries $mgCountry): Response
    {
        $form = $this->createForm(CountriesType::class, $mgCountry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('countries_index');
        }

        return $this->render('mg_countries/edit.html.twig', [
            'mg_country' => $mgCountry,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/display/{id}", name="country_active", methods="GET")
     */
    public function display(Request $request, MgCountries $country)
    {
        if (!$country->getActive()) {
            $country->setOffline(true);
            $message = 'Les livraisons sont possibles dans ce pays.';
        } else {
            $country->setActive(false);
            $message = 'Les livraisons ne sont pas possibles dans ce pays.';
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($country);
        $entityManager->flush();

        return $this->json(['code' => 200, 'message' => $message], 200);
    }
}
