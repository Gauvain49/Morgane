<?php
namespace App\Services;

use App\Entity\MgProducts;
use App\Repository\MgProductsRepository;
//use App\Repository\MgTaxesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
	protected $session;
	protected $products;
	//protected $taxes;

	public function __construct(SessionInterface $session, MgProductsRepository $products) {
		$this->session = $session;
		$this->products = $products;
		//$this->taxes = $taxes;
	}

    /**
     * Récupère le contenu du panier
     */
    public function cart(): array
    {
        if (!$this->session->has('cart')) {
            $this->session->set('cart', []);
        }

        return $cart = $this->session->get('cart');
    }

    /**
     * Ajoute un produit dans le panier
     * 
     * @param int id est l'id du produit
     * @param float amount est le prix net TTC du produit
     * @param float est le prix net TTC du produit
     * @param int quantity et la quantité du produit
     * @param string version est la version [physique, numeric] du produit
     */
    public function add(MgProducts $product, array $amount, ?int $quantity)
    {
    	if (!$this->session->has('cart')) {
    		$this->session->set('cart', []);
    	}
    	$cart = $this->session->get('cart');

        //Si l'article est déjà présent dans le panier, on rajoute une unité dans la ligne existante...
        if (array_key_exists($product->getId(), $cart)) {
            $this->more($product);
        } else {//...sinon on créée la ligne
    		if ($quantity != null) {
    			$qty = $quantity;
    		} else {
    			$qty = $product->getMinQuantity();
    		}

            $cart[$product->getId()] = [
                'product' => $product,
    	  		'qty' => $qty,
    	    	'amount' => $amount,
                'title' => $product->getContents()[0]->getName(),
    	    ];

        $this->session->set('cart', $cart);
        }
    }

    /**
     * Mise à jour des montants du panier après vérification d'une
     * eventuelle remise groupe client
     */
    public function updateAmount($key, array $amount)
    {
        $cart = $this->session->get('cart');
        $cart[$key]['amount'] = $amount;
        $this->session->set('cart', $cart);
    }

    /**
     * Augmente la quantité d'un produit dans le panier
     */
    public function more(MgProducts $product)
    {
        $cart = $this->session->get('cart');
        if (array_key_exists($product->getId(), $cart)) {
            $cart[$product->getId()]['qty'] += $product->getSalesUnit();
            $this->session->set('cart', $cart);
        }
    }

    /**
     * Diminue la quantité d'un produit dans le panier
     */
    public function less(MgProducts $product)
    {
        $cart = $this->session->get('cart');
        //$product = $this->products->find($id);
        if (array_key_exists($product->getId(), $cart)) {
            $cart[$product->getId()]['qty'] -= $product->getSalesUnit();
            $this->session->set('cart', $cart);
            if ($cart[$product->getId()]['qty'] <= 0) {
                $this->remove($product);
            }
        }
    }

    /**
     * Supprime une ligne du panier
     */
    public function remove(MgProducts $product)
    {
        $cart = $this->session->get('cart');
        if (array_key_exists($product->getId(), $cart)) {
            unset($cart[$product->getId()]);
            $this->session->set('cart', $cart);
        }
    }

    public function getShipping()
    {
        //Nombre d'article soumis au frais de livraison (ne comprends pas les articles numériques)
        $submittedToTheDelivery = 0;
        $shipping = ['price' => 0, 'taxes' => 0];

        //Calcul du nombre d'article soumis à la livraison
        foreach ($this->cart() as $item) {
            //dd($item);
            if ($item['product']->getType() != 'downloadable') {
                $submittedToTheDelivery += $item['qty'];
            }
        }

        if($submittedToTheDelivery == 1) {
            $shipping['price'] = 2.08;
            $shipping['taxes'] = 0.42;
        } elseif($submittedToTheDelivery >= 1 && $submittedToTheDelivery <= 3) {
            $shipping['price'] = 3.75;
            $shipping['taxes'] = 0.75;
        } elseif($submittedToTheDelivery >= 4 && $submittedToTheDelivery <= 6) {
            $shipping['price'] = 5;
            $shipping['taxes'] = 1;
        } elseif($submittedToTheDelivery >= 7 && $submittedToTheDelivery <= 12) {
            $shipping['price'] = 6.67;
            $shipping['taxes'] = 1.33;
        } elseif($submittedToTheDelivery > 12) {
            $shipping['price'] = 8.33;
            $shipping['taxes'] = 1.67;
        }
        return $shipping;
    }

    /**
     * Afficher le total du panier sans taxe
     */
    public function totalCartWT() : float
    {
        $cart = $this->session->get('cart', []);
        $totalCartWT = 0;
        foreach ($cart as $key => $value) {
            $totalCartWT += $value['amount']['priceNet'] * $value['qty'];
        }
        return $totalCartWT;
    }

    /**
     * Afficher le total d'un panier
     */
    public function totalCart() : float
    {
        $cart = $this->session->get('cart', []);
        $totalCart = 0;
        foreach ($cart as $key => $value) {
            //dd($value['amount']['priceNetAllTaxes']);
            $totalCart += $value['amount']['priceNetAllTaxes'] * $value['qty'];
        }
        return $totalCart;
    }

    /**
     * Afficher le nombre d'article mis dans le panier
     */
    public function totalQuantity() : float
    {
        $cart = $this->session->get('cart', []);
        $quantity = 0;
        foreach ($cart as $value) {
            $quantity += $value['qty'];
        }
        return $quantity;
    }
}