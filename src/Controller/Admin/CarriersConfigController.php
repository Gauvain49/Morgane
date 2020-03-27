<?php

namespace App\Controller\Admin;

use App\Entity\MgCarriers;
use App\Entity\MgCarriersAmountCountries;
use App\Entity\MgCarriersConfig;
use App\Form\CarriersConfigType;
use App\Repository\MgCarriersConfigRepository;
use App\Repository\MgCarriersStepsDepRepository;
use App\Repository\MgCarriersStepsRegionsRepository;
use App\Repository\MgCarriersStepsRepository;
use App\Repository\MgCountriesRepository;
use App\Repository\MgDepartmentsFrenchRepository;
use App\Repository\MgRegionsFrenchRepository;
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
     * @Route("/{id}/edit", name="carriers_config_edit", methods={"GET","POST"})
     */
    public function edit($id, Request $request, MgCarriers $carriers, MgCarriersConfigRepository $repoCarrierConfig, MgCarriersStepsRepository $repoSteps, MgCountriesRepository $repoCountry): Response
    {
        $carriersConfig = $repoCarrierConfig->findOneBy(['carrier' => $carriers]);
        if (!empty($carriersConfig)) {
            $carrierStep = $repoSteps->findBy(['config' => $carriersConfig]);
            $countStep = count($carrierStep);
        } else {
            $countStep = 0;
        }
        $countries = $repoCountry->findByActive(true);
        if (empty($carriersConfig)) {
            $carriersConfig = new MgCarriersConfig();
        }
        $form = $this->createForm(CarriersConfigType::class, $carriersConfig, [
            'required_amount' => 'country'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
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
     * @Route("/{id}/edit-department", name="carriers_config_edit_department", methods={"GET","POST"})
     */
    public function editDepartment($id, Request $request, MgCarriers $carriers, MgCarriersConfigRepository $repoCarrierConfig, MgCarriersStepsDepRepository $repoSteps, MgDepartmentsFrenchRepository $repoDepartment): Response
    {
        $carriersConfig = $repoCarrierConfig->findOneBy(['carrier' => $carriers]);
        if (!empty($carriersConfig)) {
            $carrierStep = $repoSteps->findBy(['config' => $carriersConfig]);
            $countStep = count($carrierStep);
        } else {
            $countStep = 0;
        }
        $departments = $repoDepartment->findAll();
        if (empty($carriersConfig)) {
            $carriersConfig = new MgCarriersConfig();
        }
        $form = $this->createForm(CarriersConfigType::class, $carriersConfig, [
            'required_amount' => 'departments'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $em = $this->getDoctrine()->getManager();
            foreach ($carriersConfig->getStepsDeps() as $step) {
                $step->setConfig($carriersConfig);
                $em->persist($step);
                foreach ($step->getAmountDepartments() as $amountDepartment) {
                    $amountDepartment->setCarrierStep($step);
                    $amountDepartment->setCarrierConfig($carriersConfig);
                    $em->persist($amountDepartment);
                }
            }
            $carriersConfig->setCarrier($carriers);
            $em->persist($carriersConfig);
            $em->flush();
            $this->addFlash(
                'success',
                "Enregistrement réussi."
            );

            return $this->redirectToRoute('carriers_config_edit_department', ['id' => $id]);
        }

        return $this->render('admin/carriers/config/edit_department.html.twig', [
            'carriers_config' => $carriersConfig,
            'id' => $id,
            'form' => $form->createView(),
            'departments' => $departments,
            'countStep' => $countStep
        ]);
    }

    /**
     * @Route("/{id}/edit_region", name="carriers_config_edit_region", methods={"GET","POST"})
     */
    public function editRegion($id, Request $request, MgCarriers $carriers, MgCarriersConfigRepository $repoCarrierConfig, MgCarriersStepsRegionsRepository $repoSteps, MgRegionsFrenchRepository $repoRegion): Response
    {
        $carriersConfig = $repoCarrierConfig->findOneBy(['carrier' => $carriers]);
        if (!empty($carriersConfig)) {
            $carrierStep = $repoSteps->findBy(['config' => $carriersConfig]);
            $countStep = count($carrierStep);
        } else {
            $countStep = 0;
        }
        $regions = $repoRegion->findAll();
        if (empty($carriersConfig)) {
            $carriersConfig = new MgCarriersConfig();
        }
        $form = $this->createForm(CarriersConfigType::class, $carriersConfig, [
            'required_amount' => 'regions'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            //dd($task);
            $em = $this->getDoctrine()->getManager();
            foreach ($carriersConfig->getStepsRegions() as $step) {
                $step->setConfig($carriersConfig);
                //$step->setCarrier($carriers);
                $em->persist($step);
                foreach ($step->getAmountRegions() as $amountRegion) {
                    $amountRegion->setCarrierStep($step);
                    $amountRegion->setCarrierConfig($carriersConfig);
                    //$amountCountry->setStepCountry($repoCountry->find(17));
                    //$amountCountry->setCarrier($carriers);
                    $em->persist($amountRegion);
                }
            }
            $carriersConfig->setCarrier($carriers);
            $em->persist($carriersConfig);
            $em->flush();
            $this->addFlash(
                'success',
                "Enregistrement réussi."
            );

            return $this->redirectToRoute('carriers_config_edit_region', ['id' => $id]);
        }

        return $this->render('admin/carriers/config/edit_region.html.twig', [
            'carriers_config' => $carriersConfig,
            'id' => $id,
            'form' => $form->createView(),
            'regions' => $regions,
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
