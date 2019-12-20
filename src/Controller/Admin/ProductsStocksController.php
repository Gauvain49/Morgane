<?php

namespace App\Controller\Admin;

use App\Entity\MgProducts;
use App\Form\StocksType;
use App\Services\Product\UpdateStock;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products/stock")
 */

class ProductsStocksController extends AbstractController
{
    /**
     * @Route("/{id}", name="products_stock", requirements={"id"="\d+"})
     */
    public function index(MgProducts $product, Request $request, UpdateStock $movements)
    {
        $form = $this->createForm(StocksType::class, $product);
        $quantity = $product->getQuantity();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //$task = $form->getData();
            $moreQuantity = $request->get('moreQuantity');
            if (!empty($moreQuantity) || $moreQuantity != 0) {
                $product->setQuantity($quantity + $moreQuantity);
            }
            $entityManager->persist($product);
            $entityManager->flush();

            if (!empty($moreQuantity) || $moreQuantity != 0) {
                if ($moreQuantity > 0) {
                    $info = "Ajout au stock.";
                } else {
                    $info = "Retrait du stock.";
                }
                $changeQuantity = $moreQuantity;
            } else {
                $info = 'Modification manuelle.';
                $changeQuantity = $product->getQuantity();
            }

            $movements->updateStock($product, $changeQuantity, $info, $product->getQuantity());

            $this->addFlash(
                'success',
                "Modification réussie !"
            );

            return $this->redirectToRoute('products_stock', ['id' => $product->getId()]);
        }
        return $this->render('admin/products/stock/index.html.twig', [
            'product' => $product,
            'id' => $product->getId(),
            'form' => $form->createView(),
            'NavCatalogOpen' => true
        ]);
    }
}