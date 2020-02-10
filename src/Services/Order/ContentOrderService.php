<?php
namespace App\Services\Order;

use App\Entity\MgOrders;
use App\Entity\MgOrdersContent;
use App\Entity\MgProducts;
use App\Services\CartService;
use App\Services\Order\TaxeOrderService;
use App\Services\Product\UpdateQuantity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContentOrderService extends AbstractController
{
	protected $cart;
	protected $updateQuantity;
	protected $taxeOrderService;

	public function __construct(CartService $cartServices, UpdateQuantity $updateQuantity, TaxeOrderService $taxeOrderService)
	{
		$this->cart = $cartServices;
		$this->updateQuantity = $updateQuantity;
		$this->taxeOrderService = $taxeOrderService;
	}

	public function createDetailOrder($cart, MgOrders $order)
	{
		$em = $this->getDoctrine()->getManager();
		foreach ($cart as $key => $value) {
			//dd($value['product']->getStockManagement());
			$content = new MgOrdersContent();
			$content->setGetOrder($order);
			$content->setProduct($value['product']->getId());
			$content->setFormat($value['product']->getType());
			$content->setReference($value['product']->getReference());
			$content->setDesignation($value['title']);
			$content->setQuantity($value['qty']);
			$content->setGrossUnitPrice($value['amount']['grossPrice']);
			$content->setGrossUnitTax($value['amount']['grossPriceTax']);
			$content->setGrossUnitPriceAllTaxes($value['amount']['grossPriceAllTaxes']);
			$content->setAmountDiscount($value['amount']['grossPrice'] - $value['amount']['priceNet']);
			$content->setNatureDiscount($value['amount']['detailDiscount']);
			$content->setNetUnitPrice($value['amount']['priceNet']);
			$content->setNetUnitTax($value['amount']['netPriceTax']);
			$content->setNetUnitPriceAllTaxes($value['amount']['priceNetAllTaxes']);
			$content->setTotalNetPrice($value['amount']['priceNet'] * $value['qty']);
			$content->setTotalNetTax($value['amount']['netPriceTax'] * $value['qty']);
			$content->setTotalPriceAllTaxes($value['amount']['priceNetAllTaxes'] * $value['qty']);
			//$em = $this->getDoctrine()->getManager();
			$em->persist($content);
	        $em->flush();

	        //Mise à jour du stock si besoin
	        if ($value['product']->getStockManagement() ==  true) {
	        	$this->updateQuantity->updateQuantity($value['product'], $value['qty'], $order->getNum());
	        }

	        //Insertion des lignes de taxes
	        $this->taxeOrderService->createTaxeOrder($content, $value['product']->getTaxe()->getId());

		}
		//dd($cart);
	}
}