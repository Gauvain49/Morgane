<?php

namespace App\Controller;

use App\Repository\MgCarriersRepository;
use App\Repository\MgCustomersRepository;
use App\Repository\MgGendersRepository;
use App\Repository\MgPaymentsModesRepository;
use App\Repository\MgPostsRepository;
use App\Repository\MgProductsImagesRepository;
use App\Repository\MgProductsRepository;
use App\Services\CartService;
use App\Services\Languages;
use App\Services\ShippingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/content", name="cart")
     */
    public function cart(SessionInterface $session, CartService $cartService, ShippingService $shippingService, MgProductsImagesRepository $productsImagesRepository, MgCarriersRepository $repoCarrier, MgCustomersRepository $repoCustomer)
    {
        if (!$session->has('shipping')) {
            $session->set('shipping', []);
        }
        $cart = $cartService->cart();
        $bulk = $cartService->checkBulk();
        if (is_null($this->getUser())) {
            $country = 8;
            $dep = null;
        } else {
            //On récupère le client
            $customer = $repoCustomer->findOneBy(['user' => $this->getUser()]);
            //Puis on récupère ses adresses
            if ((count($customer->getAddresses())) == 1) {
                $country = $shippingAddress = $customer->getAddresses()[0]->getCountry()->getId();
                $dep = $shippingAddress = $customer->getAddresses()[0]->getZipcode();
            } else {
                foreach ($customer->getAddresses() as $value) {
                    if ($value->getTypeAddress() == 1) {
                        $country = $value->getCountry()->getId();
                        $dep = $value->getZipcode();
                    }
                }
            }
            $dep = substr($dep, 0, 2);
        }
        $shipping = $shippingService->getShipping($country, $dep);
        //dump($shipping);
        //$session->set('shipping', $shipping);
        //Récupération des images
        $images = [];
        foreach ($cart as $key => $item) {
            $image = $productsImagesRepository->findOneBy(['product' => $key, 'cover' => true]);
            if(!empty($image)) {
                $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::SMALL, 'square');
                if (!is_null($getImage)) {
                    $images[$key] = $getImage;
                }
            }
        }
        //dd($shipping);
        $carriers = $repoCarrier->findAll();
        return $this->render('main/cart/index.html.twig', [
            'cart' => $cart,
            'images' => $images,
            'bulk' => $bulk,
            'shipping' => $shipping,
            'carriers' => $carriers
        ]);
    }

    /**
     * Ajoute un produit dans le panier
     * @Route("cart/add", name="cart_add", methods="GET|POST")
     */
    public function addCart(SessionInterface $session, CartService $cartService, Languages $languages, MgProductsRepository $repoProduct, Request $request)
    {
    	$cart = $session->get('cart');
        //dump($cart);
        if (is_numeric($request->request->get('add_cart'))) {
            $product = $repoProduct->getProduct($request->request->get('add_cart'), $languages->languageDefault());
            if (!empty($product)) {
                //On calcul d'abord le prix du produit
                //Étape 1 : Si un client est connecté, on vérifie s'il appartient à un groupe qui possède une remise
                $rateGroupCustomer = 0;
                $namegroupCustomer = false;
                if (!empty($this->getUser())) {
                    //Si le client appartient à un groupe
                    if (!is_null($this->getUser()->getCustomers()->getCustomerGroup())) {
                        //Si le groupe à une remise à 0%
                        if ($this->getUser()->getCustomers()->getCustomerGroup()->getGroupDiscount() > 0) {
                            $rateGroupCustomer = $this->getUser()->getCustomers()->getCustomerGroup()->getGroupDiscount();
                            $namegroupCustomer = $this->getUser()->getCustomers()->getCustomerGroup()->getGroupName();
                            
                        }
                    }
                }
                $prices = $repoProduct->getPrice($product, $rateGroupCustomer, $namegroupCustomer, $languages->languageDefault());

                $add = $cartService->add($product, $prices, intval($request->request->get('qty')));
                $cart = $session->get('cart');

                $title = $cart[$product->getId()]['title'];

                $this->addFlash(
                    'success',
                    "<i>'$title'</i> est ajouté à votre panier."
                );

            } else {
                $this->addFlash(
                    'danger',
                    "Ce produit n'existe pas !"
                );
            }

            //Redirection
            return $this->redirectToRoute('cart');
        } else {
            $this->addFlash(
                'danger',
                "Une anomalie s'est glissée dans l'ajout au panier !"
            );
        }

        return $this->render('main/cart/index.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * @Route("cart/less/{id}", name="cart_less", methods="GET|POST")
     */
    public function cartLess($id, SessionInterface $session, CartService $cartService, MgProductsRepository $repoProduct)
    {
        $cart = $session->get('cart');
        //$product = $repoProduct->find($id);
        if (array_key_exists($id, $cart)) {
            $less = $cartService->less($cart[$id]['product']);
        }
        if (array_key_exists($id, $cart)) {
            $session->getFlashBag()->add('success', 'Quantité mise à jour');
        } else {
            $this->session->getFlashBag()->add('success', 'Article supprimé du panier');
        }
        //$session->set('cart', $cart);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("cart/more/{id}", name="cart_more", methods="GET|POST")
     */
    public function cartMore($id, SessionInterface $session, CartService $cartService, MgProductsRepository $repoProduct)
    {
        $cart = $session->get('cart');
        if ($cart[$id]['product']->getMaxQuantity() == 0) {
            $add = $cartService->more($cart[$id]['product']);
            $session->getFlashBag()->add('success', 'Quantité mise à jour');
        } else {
            if ($cart[$id]['qty'] >= $cart[$id]['product']->getMaxQuantity()) {
                $session->getFlashBag()->add('warning', 'Vous avez atteint la limite de commande pour cet article');
            } else {
                $add = $cartService->more($cart[$id]['product']);
                $session->getFlashBag()->add('success', 'Quantité mise à jour');
            }
        }

        return $this->redirectToRoute('cart');
    }

    /**
     * Supprime une ligne du panier
     *
     * @Route("cart/cart_dell/{id}", name="cart_dell_line", methods="GET|POST")
     */
    public function remove($id, SessionInterface $session, CartService $cartService)
    {
        $cart = $session->get('cart');
        if (array_key_exists($id, $cart)) {
            $cartService->remove($cart[$id]['product']);
            $session->getFlashBag()->add('success', 'Article supprimé avec succès');
        }
        return $this->redirectToRoute('cart');
    }

    /**
     * Après la connexion d'un visiteur, vérifie s'il faut appliquer
     * des remises exceptionnelles aux produits du panier
     *
     * @Route("cart/cart_check_discount", name="cart_check_discount")
     */
    public function checkDiscountAfterLogin(SessionInterface $session, CartService $cartService, MgProductsRepository $repoProduct, Languages $languages)
    {
        $cart = $cartService->cart();
        $nature_discount = [];
        foreach ($cart as $key => $value) {
            $product = $repoProduct->getProduct($key, $languages->languageDefault());
            //Si le client appartient à un groupe
            if (!is_null($this->getUser()->getCustomers()->getCustomerGroup())) {
                //Si le groupe à une remise supérieur à 0%
                if ($this->getUser()->getCustomers()->getCustomerGroup()->getGroupDiscount() > 0) {
                    //Si le groupe à une remise supérieur à 0%
                    //if ($this->getUser()->getCustomerGroup()->getGroupDiscount() > 0) {
                        $rateGroupCustomer = $this->getUser()->getCustomers()->getCustomerGroup()->getGroupDiscount();
                        $namegroupCustomer = $this->getUser()->getCustomers()->getCustomerGroup()->getGroupName();
                        $prices = $repoProduct->getPrice($product, $rateGroupCustomer, $namegroupCustomer, $languages->languageDefault());
                        // Si le prix net du panier (qui prend déjà en compte une eventuelle remise produit) est supérieur
                        // au prix calculé avec la remise groupe client, c'est cette dernière qui est prise en compte
                        if ($value['amount']['priceNetAllTaxes'] > $prices['priceNetAllTaxes']) {
                            $update = $cartService->updateAmount($key, $prices);
                            //$cart[$key]['amount'] = $prices;
                        }
                    //}
                }
            }
        }
        //$session->set('cart', $cart);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("cart/confirm", name="cart_confirm")
     */
    public function confirm(SessionInterface $session, MgProductsRepository $repoProduct, MgProductsImagesRepository $productsImagesRepository, MgCarriersRepository $repoCarrier, CartService $cartService, MgPaymentsModesRepository $repoPayments, MgCustomersRepository $repoCustomer, MgGendersRepository $repoGender, MgPostsRepository $repoPost)
    {
        //Si la session du panier ou des frais de livraison n'existe pas...
        if (!$session->has('shipping')) {
            $session->set('shipping', []);
        }

        //On récupère le client
        $customer = $repoCustomer->findOneBy(['user' => $this->getUser()]);
        //Puis on récupère ses adresses
        if ((count($customer->getAddresses())) == 1) {
            $billingAddress = $shippingAddress = $customer->getAddresses()[0];
        } else {
            foreach ($customer->getAddresses() as $value) {
                if ($value->getTypeAddress() == 0) {
                    $billingAddress = $value;
                } else {
                    $shippingAddress = $value;
                }
            }
        }

        $cart = $cartService->cart();

        //$shipping = $session->get('shipping');
        //$totalCart = $cartService->totalCart();
        //Récupération des images
        $images = [];
        foreach ($cart as $key => $item) {
            $image = $productsImagesRepository->findOneBy(['product' => $key, 'cover' => true]);
            if(!empty($image)) {
                $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::SMALL, 'square');
                if (!is_null($getImage)) {
                    $images[$key] = $getImage;
                }
            }
        }
        $genders = $repoGender->findAll();
        //$session->set('shipping', $cartService->getShipping($cart));
        $payments = $repoPayments->findByActif(true);
        $cgv = $repoPost->findOneByReserved('CGV');
        foreach ($cgv->getContents() as $value) {
            if ($value->getLang()->getId() == 1) {
                $cgv = $value->getContent();
            }
        }
        $carriers = $repoCarrier->findAll();
        return $this->render('main/cart/confirmCart.html.twig', [
            'cart' => $cart,
            //'pricesProducts' => $pricesProducts,
            //'totalCart' => $totalCart,
            //'totalCartByTaxes' => $totalCartByTaxes,
            //'totalAllTaxes' => $totalAllTaxes,
            //'authors' => $authors,
            'images' => $images,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
            'genders' => $genders,
            'shipping' => current($session->get('shipping')),
            'payments' => $payments,
            'cgv' => $cgv,
            'carriers' => $carriers
        ]);
    }
}
