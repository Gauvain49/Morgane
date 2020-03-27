<?php
namespace App\Services;

use App\Entity\MgProducts;
use App\Repository\MgCarriersRepository;
use App\Repository\MgDepartmentsFrenchRepository;
use App\Repository\MgProductsRepository;
use App\Repository\MgTaxesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
	protected $session;
	protected $products;

	public function __construct(SessionInterface $session, MgProductsRepository $products) {
		$this->session = $session;
		$this->products = $products;
	}

    /**
     * Récupère le contenu du panier
     */
    public function cart(): array
    {
        if (!$this->session->has('cart')) {
            $this->session->set('cart', []);
        }

        return $this->session->get('cart');
    }

    /**
     * Vérifie que le panier est valide en cas de produit commandable en vrac
     */
    public function checkBulk()
    {
        $cart = $this->cart();
        $productBulk = []; //Tableau recueillant l'analyse du vrac
        $bulk = null; //Variable recueillant la valeur du vrac servant de comparaison avec les produits mis en panier
        $totalBulk = 0; //Valeur recueillant le total vrac définie pour les produits
        $qtyCartBulk = 0; //Valeur reprenant les quantités des produits vrac mis dans le panier
        foreach ($cart as $value) {
            if ($value['product']->getBulkQuantity() > 1) {
                $productBulk['exist'] = true;
                $qtyCartBulk += $value['qty'];
                $totalBulk += $value['product']->getBulkQuantity();
            }
            if ($value['product']->getBulkQuantity() > $bulk) {
                $bulk = $value['product']->getBulkQuantity();
            }
        }

        if (($totalBulk == $qtyCartBulk) || (($bulk % $qtyCartBulk) == 0 && ($bulk/$qtyCartBulk) == 1)) {
            $productBulk['valid'] = true;
        } else {
            $productBulk['valid'] = false;
        }
        return $productBulk;
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
    	/*if (!$this->session->has('cart')) {
    		$this->session->set('cart', []);
    	}
    	$cart = $this->session->get('cart');*/
        $cart = $this->cart();

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
        //$cart = $this->session->get('cart');
        $cart = $this->cart();
        $cart[$key]['amount'] = $amount;
        $this->session->set('cart', $cart);
    }

    /**
     * Augmente la quantité d'un produit dans le panier
     */
    public function more(MgProducts $product)
    {
        //$cart = $this->session->get('cart');
        $cart = $this->cart();
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
        //$cart = $this->session->get('cart');
        $cart = $this->cart();
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
        //$cart = $this->session->get('cart');
        $cart = $this->cart();
        if (array_key_exists($product->getId(), $cart)) {
            unset($cart[$product->getId()]);
            $this->session->set('cart', $cart);
        }
    }

    /**
     * Afficher le total du panier sans taxe
     */
    public function totalCartWT() : float
    {
        //$cart = $this->session->get('cart', []);
        $cart = $this->cart();
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
        //$cart = $this->session->get('cart', []);
        $cart = $this->cart();
        $totalCart = 0;
        foreach ($cart as $key => $value) {
            $totalCart += $value['amount']['priceNetAllTaxes'] * $value['qty'];
        }
        return $totalCart;
    }

    /**
     * Afficher le nombre d'article mis dans le panier
     */
    public function totalQuantity() : float
    {
        //$cart = $this->session->get('cart', []);
        $cart = $this->cart();
        $quantity = 0;
        foreach ($cart as $value) {
            $quantity += $value['qty'];
        }
        return $quantity;
    }
}