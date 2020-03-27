<?php
namespace App\Services\Order;

use App\Entity\MgGenders;
use App\Entity\MgOrders;
use App\Entity\MgOrdersStatusLang;
use App\Entity\MgUsers;
use App\Services\CartService;
use App\Services\Order\StatusOrderService;
use App\Services\ShippingService;
use App\Services\TokenUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderService extends AbstractController
{
	protected $cart;
	protected $token;
	protected $contentOrder;
	protected $statusOrder;
	protected $shipping;

	public function __construct(CartService $cartServices, ShippingService $shippingService, TokenUtils $token, ContentOrderService $contentOrder, StatusOrderService $statusOrder)
	{
		$this->cart = $cartServices;
		$this->token = $token;
		$this->contentOrder = $contentOrder;
		$this->statusOrder = $statusOrder;
		$this->shipping = $shippingService;
	}

	/**
	 * Permet la création d'une nouvelle commande
	 */

	public function creatOrder(MgUsers $user, $mode_payment, $numOrder)
	{
		$cart = $this->cart->cart();
		$shipping = $this->shipping->totalCostShipping();

		//Récupération des adresses du client
		$country_shipping = '';
		$country_billing = '';
		$addresses = $user->getCustomers()->getAddresses();

		foreach ($addresses as $key => $value) {
			if (count($addresses) == 1) {
				$address_billing = $address_shipping = $value;
				$civilityBilling = $civilityShipping = $this->getDoctrine()->getRepository(MgGenders::class)->find($value->getGender())->getShortGender();
				//dd($value->getGender());
                foreach ($value->getCountry()->getCountriesLangs() as $v) {
                	if ($v->getLang()->getId() == 1) {
                		$country_billing = $country_shipping = $v->getCountryName();
                	}
                }
			} else {//Sinon, on initialise les 2 adresses par celles définies par le client
	            if ($value->getTypeAddress()) {
	                $address_shipping = $value;
					$civilityShipping = $this->getDoctrine()->getRepository(MgGenders::class)->find($value->getGender())->getShortGender();
	                foreach ($value->getCountry()->getCountriesLangs() as $v) {
	                	if ($v->getLang()->getId() == 1) {
	                		$country_shipping = $v->getCountryName();
	                	}
	                }
	            } else {
	                $address_billing = $value;
					$civilityBilling = $this->getDoctrine()->getRepository(MgGenders::class)->find($value->getGender())->getShortGender();
	                foreach ($value->getCountry()->getCountriesLangs() as $v) {
	                	if ($v->getLang()->getId() == 1) {
	                		$country_billing = $v->getCountryName();
	                	}
	                }
	            }
			}
		}

		/**
		 * Montant total du panier
		 */
		$totalCartNoTax = $this->cart->totalCartWT();
		$totalCartAllTaxes = $this->cart->totalCart();

		//Création du numéro de la commande
		//$numOrder = strtoupper($this->token->generateToken(5));
		//$numOrder .= $this->getDoctrine()->getRepository(MgOrders::class)->getNumOrder();

		//Récupération du status
		if ($mode_payment == 'paypal') {
			$status = $this->getDoctrine()->getRepository(MgOrdersStatusLang::class)->find(14);
		} elseif ($mode_payment == 'check') {
			$status = $this->getDoctrine()->getRepository(MgOrdersStatusLang::class)->find(1);
		} elseif ($mode_payment == 'clickandpay') {
			$status = $this->getDoctrine()->getRepository(MgOrdersStatusLang::class)->find(11);
		} else {
			$status = $this->getDoctrine()->getRepository(MgOrdersStatusLang::class)->find(14);
		}

		//Création de la commande
		$em = $this->getDoctrine()->getManager();

		$order = new MgOrders();
		$order->setUser($user);
		$order->setNum($numOrder);
		$order->setBillingName($civilityBilling . ' ' . $address_billing->getAddressFirstname() . ' ' . $address_billing->getAddressLastname());
		$order->setBillingAddress($address_billing->getAddress() . "\n" . $address_billing->getZipcode() . ' ' . $address_billing->getTown() . "\n" . $country_billing);
		$order->setDeliveryName($civilityShipping . ' ' . $address_shipping->getAddressFirstname() . ' ' . $address_shipping->getAddressLastname());
		$order->setDeliveryAddress($address_shipping->getAddress() . "\n" . $address_shipping->getZipcode() . ' ' . $address_shipping->getTown() . "\n" . $country_shipping);
		$order->setTotalPrice($totalCartNoTax);
		$order->setTotalTaxes($totalCartAllTaxes - $totalCartNoTax);
		$order->setTotalPriceAllTaxes($totalCartAllTaxes);
		$order->setTotalShippingPrice($shipping['price']);
		$order->setTotalShippingTaxes($shipping['taxes']);
		$order->setUniqKey($this->token->generateUniqid());
		
		$order->setCurrentStatus($status);
		$em->persist($order);
        $em->flush();

        $this->contentOrder->createDetailOrder($cart, $order);
        $this->statusOrder->createStatusOrder($order, $mode_payment);

        return $order;
	}
}