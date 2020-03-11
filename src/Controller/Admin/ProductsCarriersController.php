<?php

namespace App\Controller\Admin;

use App\Entity\MgProducts;
use App\Form\ProductCarrierType;
use App\Services\Product\UpdateStock;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products/carrier")
 */

class ProductsCarriersController extends AbstractController
{
    /**
     * @Route("/{id}", name="products_carrier", requirements={"id"="\d+"})
     */
    public function index(MgProducts $product, Request $request)
    {
        $form = $this->createForm(ProductCarrierType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();


            $this->addFlash(
                'success',
                "Modification réussie !"
            );

            return $this->redirectToRoute('products_carrier', ['id' => $product->getId()]);
        }
        return $this->render('admin/products/carrier/index.html.twig', [
            'product' => $product,
            'id' => $product->getId(),
            'form' => $form->createView(),
            'NavCatalogOpen' => true
        ]);
    }
}