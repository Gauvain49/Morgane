<?php

namespace App\Controller\Admin;

use App\Entity\MgProducts;
use App\Entity\MgProductsLang;
use App\Form\ProductsAuthorsType;
use App\Form\ProductsType;
use App\Repository\MgAuthorsRepository;
use App\Repository\MgCategoriesRepository;
use App\Repository\MgProductsImagesRepository;
use App\Repository\MgProductsNumericalRepository;
use App\Repository\MgProductsRepository;
use App\Services\AppService;
use App\Services\DeleteItems;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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
            'products' => $ProductsRepository->findBy(['type' => 'master'], ['id' => 'DESC']),
            'NavCatalogOpen' => true
        ]);
    }

    /**
     * @Route("/display/{id}", name="products_display", methods="GET")
     */
    public function display(Request $request, MgProducts $product)
    {
        if (!$product->getOffline()) {
            $product->setOffline(true);
            $message = 'Le product n\'apparaît plus dans le catalogue.';
        } else {
            $product->setOffline(false);
            $message = 'Le product apparaît dans le catalogue.';
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json(['code' => 200, 'message' => $message], 200);
    }

    /**
     * @Route("/new", name="products_new", methods={"GET","POST"})
     */
    public function new(Request $request, MgCategoriesRepository $repoCat): Response
    {
        $product = new MgProducts();
        $content = new MgProductsLang();
        //$listeCat = $repoCat->findAllByArborescence('product');
        //$form = $this->createForm(ProductsType::class, $product, ['checkbox' => $listeCat]);
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            //dd($task);
            if (($task->getMinQuantity() < $task->getSalesUnit()) && ($task->getMinQuantity() > 0)) {
                $form->get('min_quantity')->addError(new FormError("Ne peut etre inférieure à unité de vente'"));

                $this->addFlash(
                    'danger', 'Le produit contient des erreurs.'
                );
            } elseif(($task->getMaxQuantity() < $task->getSalesUnit()) && ($task->getMaxQuantity() > 0)) {
                $form->get('max_quantity')->addError(new FormError("Ne peut etre inférieure à unité de vente'"));

                $this->addFlash(
                    'danger', 'Le produit contient des erreurs.'
                );
            } else {
                //Si l'unité de vente est rempli mais pas le minimum commande, ce dernier prend la valeur de l'unité de vente
                if (!empty($task->getSalesUnit()) && empty($task->getMinQuantity())) {
                    $product->setMinQuantity($task->getSalesUnit());
                }
                $entityManager = $this->getDoctrine()->getManager();
                foreach ($product->getContents() as $content) {
                    $content->setProduct($product);
                    $entityManager->persist($content);
                }
                foreach ($product->getCategories() as $category) {
                    $cat = $repoCat->findOneById($category);
                    $product->addCategory($cat);
                }
                foreach ($product->getPropertiesContents() as $propertyContent) {
                    $propertyContent->setProduct($product);
                    $entityManager->persist($propertyContent);
                }
                $product->setSellOutOfStock(false);
                $product->setQuantity(0);
                //$this->getUser() reprend automatiquement dans un controller l'utilisateur connecté
                $product->setUser($this->getUser());
                $entityManager->persist($product);
                $entityManager->flush();

                $this->addFlash(
                    'success', 'Création réussie !'
                );

                return $this->redirectToRoute('products_edit', ['id' => $product->getId()]);
            }
        }

        return $this->render('admin/products/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'NavCatalogOpen' => true
        ]);
    }

    /**
     * @Route("/{id}/edit", name="products_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, MgProducts $product, MgCategoriesRepository $repoCat): Response
    {
        /*$listeCat = $repoCat->findAllByArborescence('product');
        //Récupération des catégories déjà sélectionné (en attendant de toruver mieux)
        $cat_selected = [];
        $recup_cat = $product->getCategories();
        foreach ($recup_cat as $value) {
            $cat_selected[] = $value->getId();
        }*/
        //$form = $this->createForm(ProductsType::class, $product, ['checkbox' => $listeCat]);
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($product->getContents() as $content) {
                $content->setProduct($product);
                $entityManager->persist($content);
            }
            foreach ($product->getPropertiesContents() as $propertyContent) {
                $propertyContent->setProduct($product);
                $entityManager->persist($propertyContent);
            }
            $product->addReviser($this->getUser());
            $entityManager->flush();

            $this->addFlash(
                'success', 'Modification réussie !'
            );

            return $this->redirectToRoute('products_edit', ['id' => $product->getId()]);
        }

        return $this->render('admin/products/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'id' => $product->getId(),
            //'catSelected' => $cat_selected,
            'NavCatalogOpen' => true
        ]);
    }

    /**
     * @Route("/{id}/authors", name="products_authors", requirements={"id"="\d+"})
     */
    public function setAuthors(MgProducts $product, MgAuthorsRepository $repoAuthors, Request $request)
    {
        $authors = $repoAuthors->findAll();
        $form = $this->createForm(ProductsAuthorsType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($product->getAuthors() as $author) {
                $product->addAuthor($author);
            }
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'success', 'Mis a jour auteur réussie !'
            );
        }
        return $this->render('admin/products/authors/index.html.twig', [
            'id' => $product->getId(),
            'product' => $product,
            'form' => $form->createView(),
            'authors' => $authors,
            'NavCatalogOpen' => true
        ]);
    }

    /**
     * @Route("/{id}", name="products_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgProducts $product, MgProductsRepository $repo, MgProductsImagesRepository $repoImages, MgProductsNumericalRepository $numerical, DeleteItems $deleteItems, AppService $app): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $images = $repoImages->findByProduct($product->getId());
            if (!empty($images)) {
                foreach ($images as $value) {
                    /**
                     * Effacement des images du serveur            
                     */
                    //On récupère le chemin du dossier
                    $path = $this->getParameter('upload_directory_products') . '/';
                    $fields = str_split($value->getId());
                    foreach($fields as $field) {
                        $path .= $field . '/';
                    }
                    //... on vide ce qu'il contient, puis on le détruit.
                    $deleteItems->deleteDirectoryAndHisFiles($path);
                }
            }

            //On récupère tous les produits enfants pour les supprimer
            $children = $repo->findByParent($product);
            foreach ($children as $child) {
                 //Effacement du fichier numérique associé
                $product_numerical = $numerical->findOneByProduct($child);
                if (!empty($product_numerical)) {
                    $field = $app->getHashHmac($product_numerical->getId());
                    $path = $this->getParameter('upload_directory_numericals') . '/' . $field;
                    $deleteItems->deleteNumerical($path, $product_numerical->getFilename());
                }
                $entityManager->remove($child);
            }
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le produit a bien été supprimée."
            );
        }

        return $this->redirectToRoute('products_index');
    }
}
