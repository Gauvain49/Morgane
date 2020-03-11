<?php

namespace App\Controller\Admin;

use App\Entity\MgCarriers;
use App\Entity\MgCarriersAmountCountries;
use App\Entity\MgCarriersConfig;
use App\Form\CarriersConfigType;
use App\Repository\MgCarriersConfigRepository;
use App\Repository\MgCarriersStepsRepository;
use App\Repository\MgCountriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/carriers/config")
 */
class CarriersConfigController extends AbstractController
{
    /**
     * @Route("/", name="carriers_config_index", methods={"GET"})
     */
    public function index(MgCarriersConfigRepository $mgCarriersConfigRepository): Response
    {
        return $this->render('admin/carriers/config/index.html.twig', [
            'mg_carriers_configs' => $mgCarriersConfigRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="carriers_config_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $carriersConfig = new MgCarriersConfig();
        $form = $this->createForm(CarriersConfigType::class, $carriersConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($carriersConfig);
            $entityManager->flush();

            return $this->redirectToRoute('carriers_config_index');
        }

        return $this->render('admin/carriers/config/new.html.twig', [
            'mg_carriers_config' => $carriersConfig,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="carriers_config_edit", methods={"GET","POST"})
     */
    public function edit($id, Request $request, MgCarriers $carriers, MgCarriersConfigRepository $repoCarrierConfig, MgCarriersStepsRepository $repoSteps, MgCountriesRepository $repoCountry): Response
    {
        //$carriers;
        $carriersConfig = $repoCarrierConfig->findOneBy(['carrier' => $carriers]);
        if (!empty($carriersConfig)) {
            $carrierStep = $repoSteps->findBy(['config' => $carriersConfig]);
            $countStep = count($carrierStep);
        } else {
            $countStep = 0;
        }
        $countries = $repoCountry->findByActive(true);
        //dd($countries);
        if (empty($carriersConfig)) {
            $carriersConfig = new MgCarriersConfig();
            //$amountcountries = new MgCarriersAmountCountries();
            /*$step = new MgCarriersSteps();
            $step->setStepMin(10)
                ->setStepMax(10);
                //->addAmountCountry($amountcountries);
            $carriersConfig->addStep($step);*/
        }
        //dd(count($carriersConfig));
        $form = $this->createForm(CarriersConfigType::class, $carriersConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            //dump($carriersConfig);
            foreach ($task->getSteps() as $value) {
                //dump($value->getAmountCountries());
                # code...
            }
            //dd($task->getSteps());
            $em = $this->getDoctrine()->getManager();
            foreach ($carriersConfig->getSteps() as $step) {
                $step->setConfig($carriersConfig);
                //$step->setCarrier($carriers);
                $em->persist($step);
                foreach ($step->getAmountCountries() as $amountCountry) {
                    $amountCountry->setCarrierStep($step);
                    $amountCountry->setCarrierConfig($carriersConfig);
                    //$amountCountry->setStepCountry($repoCountry->find(17));
                    //$amountCountry->setCarrier($carriers);
                    $em->persist($amountCountry);
                }
            }
            $carriersConfig->setCarrier($carriers);
            $em->persist($carriersConfig);
            $em->flush();
            $this->addFlash(
                'success',
                "Enregistrement réussi."
            );

            return $this->redirectToRoute('carriers_config_edit', ['id' => $id]);
        }

        return $this->render('admin/carriers/config/edit.html.twig', [
            'carriers_config' => $carriersConfig,
            'id' => $id,
            'form' => $form->createView(),
            'countries' => $countries,
            'countStep' => $countStep
        ]);
    }

    /**
     * @Route("/{id}", name="carriers_config_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgCarriersConfig $mgCarriersConfig): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mgCarriersConfig->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mgCarriersConfig);
            $entityManager->flush();
        }

        return $this->redirectToRoute('carriers_config_index');
    }
}
