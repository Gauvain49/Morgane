<?php

namespace App\Controller\Admin;

use App\Entity\MgCarriers;
use App\Entity\MgCarriersLang;
use App\Form\CarriersType;
use App\Repository\MgCarriersRepository;
use App\Services\DeleteItems;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarriersController extends AbstractController
{
    /**
     * @Route("/admin/carriers", name="carriers_index")
     */
    public function index(MgCarriersRepository $repoCarriers)
    {
        return $this->render('admin/carriers/index.html.twig', [
            'carriers' => $repoCarriers->findAll(),
        ]);
    }

    /**
     * @Route("/admin/carriers/new", name="carriers_new", methods={"GET","POST"})
     */
    public function new(Request $request, MgCarriersRepository $repoCarriers)
    {
    	$carrier = new MgCarriers();
    	$delay = new MgCarriersLang();
    	$ifExistCarrier = $repoCarriers->findAll();
        $form = $this->createForm(CarriersType::class, $carrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$task = $form->getData();
            //dd($task->getCarriersLang());
            $entityManager = $this->getDoctrine()->getManager();
            $file = $carrier->getCarrierLogo();
            if (!is_null($file)) {
                $carrier->setCarrierLogo($file->getClientOriginalName());
                $path = $this->getParameter('upload_directory') . '/carriers/' . $carrier->getId();
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $filename = $file->getClientOriginalName();
                try {
                    $file->move($path, $filename);
                } catch (FileException $e) {
                    die($e);
                }
            }
            foreach ($carrier->getCarriersLang() as $delay) {
            	$delay->setCarrier($carrier);
            	$entityManager->persist($delay);
            }
            if (count($ifExistCarrier) > 0) {
            	$carrier->setCarrierDefault(true);
            } else {
            	$carrier->setCarrierDefault(false);
            }
            $entityManager->persist($carrier);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "Ajout du nouveau transporteur réussi."
            );

            return $this->redirectToRoute('carriers_index');
        }

        return $this->render('admin/carriers/new.html.twig', [
            'carrier' => $carrier,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/carriers/{id}/edit", name="carriers_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgCarriers $carrier, DeleteItems $deleteItems)
    {
        $img = $carrier->getCarrierLogo();
        //$carrier = new MgCarriers();
        //$delay = new MgCarriersLang();
        //$ifExistCarrier = $repoCarriers->findAll();
        $form = $this->createForm(CarriersType::class, $carrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$task = $form->getData();
            //dd($task->getCarriersLang());
            $entityManager = $this->getDoctrine()->getManager();
            $file = $carrier->getCarrierLogo();
            if ($request->get('dellLogo') == 'on') {
                //Si une image existe déjà, on la supprime
                if(!is_null($img)) {
                    $deleteItems->deleteDirectoryAndHisFiles($this->getParameter('upload_directory') . '/carriers/' . $carrier->getId() . '/');
                }
            } else {
                if (!is_null($file)) {
                    //Si une image existe déjà, on la supprime
                    if(!is_null($img)) {
                        $deleteItems->deleteDirectoryAndHisFiles($this->getParameter('upload_directory') . '/carriers/' . $carrier->getId() . '/');
                    }
                    //On charge l'image sélectionné
                    $carrier->setCarrierLogo($file->getClientOriginalName());
                    $path = $this->getParameter('upload_directory') . '/carriers/' . $carrier->getId();
                    if (!is_dir($path)) {
                        mkdir($path, 0777, true);
                    }
                    $filename = $file->getClientOriginalName();
                    try {
                        $file->move($path, $filename);
                    } catch (FileException $e) {
                        die($e);
                    }
                } else {
                    $carrier->setCarrierLogo($img);
                }             
            }

            foreach ($carrier->getCarriersLang() as $delay) {
                $delay->setCarrier($carrier);
                $entityManager->persist($delay);
            }
            $entityManager->persist($carrier);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "Modification réussie !"
            );

            return $this->redirectToRoute('carriers_index');
        }

        return $this->render('admin/carriers/edit.html.twig', [
            'carrier' => $carrier,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/carriers/{id}", name="carriers_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgCarriers $carrier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carrier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($carrier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('carriers_index');
    }
}
