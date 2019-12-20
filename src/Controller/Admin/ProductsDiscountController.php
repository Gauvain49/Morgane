<?php

namespace App\Controller\Admin;

use App\Entity\MgProducts;
use App\Form\DiscountType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products/discount")
 */
class ProductsDiscountController extends AbstractController
{
	/**
	 * @Route("/{id}", name="discount_index", requirements={"id"="\d+"})
	 */
	public function index(MgProducts $product, Request $request)
	{
		$form = $this->createForm(DiscountType::class, $product);
    	$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        	$entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'success', 'Modification réussie !'
            );
            return $this->redirectToRoute('discount_index', [
                'id' => $product->getId()
                ]
            );
        }
        return $this->render('admin/products/discount/index.html.twig', [
            'product' => $product,
            'id' => $product->getId(),
            'form' => $form->createView(),
            'NavCatalogOpen' => true
        ]);
	}
}