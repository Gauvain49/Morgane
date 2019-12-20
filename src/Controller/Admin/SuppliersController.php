<?php

namespace App\Controller\Admin;

use App\Entity\MgSuppliers;
use App\Form\SuppliersType;
use App\Repository\MgSuppliersRepository;
use App\Services\DeleteItems;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/suppliers", name="suppliers_")
 */
class SuppliersController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(MgSuppliersRepository $suppliersRepository): Response
    {
        return $this->render('admin/suppliers/index.html.twig', [
            'suppliers' => $suppliersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $supplier = new MgSuppliers();
        $form = $this->createForm(SuppliersType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $file = $supplier->getSupplierLogo();
            if (!is_null($file)) {
                $supplier->setSupplierLogo($file->getClientOriginalName());
            }
            $entityManager->persist($supplier);
            $entityManager->flush();
            if (!is_null($file)) {
                $path = $this->getParameter('upload_directory') . '/suppliers/' . $supplier->getId();
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

            return $this->redirectToRoute('suppliers_index');

            $this->addFlash(
                'success', 'Création réussie !'
            );
        }

        return $this->render('admin/suppliers/new.html.twig', [
            'supplier' => $supplier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgSuppliers $supplier, DeleteItems $deleteItems): Response
    {
        $logo = $supplier->getSupplierLogo();
        $form = $this->createForm(SuppliersType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $file = $task->getSupplierLogo();
            //$file = $supplier->getSupplierLogo();
            if ($request->get('dellLogo') == 'on') {
                //Si une image existe déjà, on la supprime
                if(!is_null($logo)) {
                    $deleteItems->deleteDirectoryAndHisFiles($this->getParameter('upload_directory') . '/suppliers/' . $supplier->getId() . '/');
                }
            } else {
                //S'il existe une image
                if (!is_null($file)) {
                    //Si une image existe déjà, on la supprime
                    if(!is_null($logo)) {
                        $deleteItems->deleteDirectoryAndHisFiles($this->getParameter('upload_directory') . '/suppliers/' . $supplier->getId() . '/');
                    }
                    //On charge l'image sélectionné
                    $supplier->setSupplierLogo($file->getClientOriginalName());
                    $path = $this->getParameter('upload_directory') . '/suppliers/' . $supplier->getId() . '/';
                    //Si le fichier n'existe pas, on le créée
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
                    $supplier->setSupplierLogo($logo);
                }
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success', 'Modification réussie !'
            );

            return $this->redirectToRoute('suppliers_index');
        }

        return $this->render('admin/suppliers/edit.html.twig', [
            'supplier' => $supplier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgSuppliers $supplier, DeleteItems $deleteItems): Response
    {
        $logo = $supplier->getSupplierLogo();
        $id = $supplier->getId();
        if ($this->isCsrfTokenValid('delete'.$supplier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($supplier);
            $entityManager->flush();
            if(!is_null($logo)) {
                $deleteItems->deleteDirectoryAndHisFiles($this->getParameter('upload_directory') . '/suppliers/' . $id . '/');
            }
        }
        $this->addFlash(
            'danger',
            "Suppression réussie."
        );

        return $this->redirectToRoute('suppliers_index');
    }
}
