<?php

namespace App\Controller\Admin;

use App\Entity\MgProducts;
use App\Entity\MgProductsLang;
use App\Form\ProductsType;
use App\Repository\MgCategoriesRepository;
use App\Repository\MgProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products")
 */
class ProductsController extends AbstractController
{
    /**
     * @Route("/", name="products_index", methods={"GET"})
     */
    public function index(MgProductsRepository $ProductsRepository): Response
    {
        return $this->render('admin/products/index.html.twig', [
            'products' => $ProductsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="products_new", methods={"GET","POST"})
     */
    public function new(Request $request, MgCategoriesRepository $repoCat): Response
    {
        $product = new MgProducts();
        $content = new MgProductsLang();
        $listeCat = $repoCat->findAllByArborescence('product');
        $form = $this->createForm(ProductsType::class, $product, ['checkbox' => $listeCat]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($product->getContents() as $content) {
                $content->setProduct($product);
                $entityManager->persist($content);
            }
            foreach ($product->getCategories() as $category) {
                $cat = $repoCat->findOneById($category);
                $product->addCategory($cat);
            }
            $product->setSellOutOfStock(false);
            $product->setQuantity(0);
            //$this->getUser() reprend automatiquement dans un controller l'utilisateur connecté
            $product->setUser($this->getUser());
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('products_index');
        }

        return $this->render('admin/products/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="products_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, MgProducts $product, MgCategoriesRepository $repoCat): Response
    {
        $listeCat = $repoCat->findAllByArborescence('product');
        //dump($listeCat);
        //dd($listeCat);
        //Récupération des catégories déjà sélectionné (en attendant de toruver mieux)
        $cat_selected = [];
        $recup_cat = $product->getCategories();
        foreach ($recup_cat as $value) {
            $cat_selected[] = $value->getId();
        }
        $form = $this->createForm(ProductsType::class, $product, ['checkbox' => $listeCat]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('products_index');
        }

        return $this->render('admin/products/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'id' => $product->getId(),
            'catSelected' => $cat_selected,
        ]);
    }

    /**
     * @Route("/{id}", name="products_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgProducts $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('products_index');
    }
}
