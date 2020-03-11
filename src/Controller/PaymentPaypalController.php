<?php

namespace App\Controller;

use App\Repository\MgCountriesRepository;
use App\Repository\MgCustomersRepository;
use App\Repository\MgPaypalRepository;
use App\Services\AppService;
use App\Services\CartService;
use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentPaypalController extends AbstractController
{
    /**
     * @Route("cart/payment/paypal", name="payment_paypal")
     */
    public function paymentPaypal(SessionInterface $session, MgPaypalRepository $repoPaypal, CartService $cartService, MgCustomersRepository $repoCustomer, MgCountriesRepository $countryRepository, AppService $appService)
    {
    	$cart = $cartService->cart();
    	if (!empty($cart) && !empty($this->getUser())) {
	        //On récupère le client
	        $customer = $repoCustomer->findOneBy(['user' => $this->getUser()]);
	        //Puis on récupère ses adresses
	        if ((count($customer->getAddresses())) == 1) {
	            $billingAddress = $shippingAddress = $customer->getAddresses()[0];
	            $billingCountry = $shippingCountry = $countryRepository->findOneById($billingAddress->getCountry()->getId());
	        } else {
	            foreach ($customer->getAddresses() as $value) {
	                if ($value->getTypeAddress() == 0) {
	                    $billingAddress = $value;
	            		$billingCountry = $billingAddress->findOneById($billingAddress->getCountry()->getId());
	                } else {
	                    $shippingAddress = $value;
	                    $shippingCountry = $shippingAddress->findOneById($shippingAddress->getCountry()->getId());
	                }
	            }
	        }
	        //$shipping = array_sum($cartService->getShipping());
			$paypalIds = $repoPaypal->find(1);
			$ids = [];
			if ($paypalIds->getModeTest() == 0) {
				$ids = ['id' => $paypalIds->getUser(), 'secret' => $paypalIds->getSignature()];
			} else {
				$ids = ['id' => 'AUi8L06-YvEFAUW6PPZ90M2hu1etzJ6IQvEUvIva9veIPnHQZPBHl4Rx3-kCXGFDNWVDdEAabM85OR9n', 'secret' => 'ECuM9VXFrS1TCr61UcFutOksoPVPkjgLrx-GDWrNM2KFPdpg_VLIG2V-LuEls75JUFf-213mSzRr3bq5'];
			}
			$apiContext = new ApiContext(
				new OAuthTokenCredential(
					$ids['id'],
					$ids['secret']
				)
			);

			$list = new ItemList();

			foreach ($cart as $listCart) {
				$item = (new Item())
					->setName($listCart['title'])//Nom du produit
					->setPrice($listCart['amount']['priceNetAllTaxes'])
					->setCurrency('EUR')
					->setQuantity($listCart['qty']);
				$list->addItem($item);
			}
			$details = (new Details())
				->setSubtotal($cartService->totalCart())
				->setShipping(array_sum($cartService->getShipping()));

			$amount = (new Amount())
				->setTotal($cartService->totalCart() + array_sum($cartService->getShipping()))
				->setCurrency('EUR')
				->setDetails($details);

			$address = (new Address())
				->setLine1($billingAddress->getAddress())
				->setCity($billingAddress->getTown())
				->setCountryCode($billingCountry->getIsoCode())
				->setPostalCode($billingAddress->getZipcode());

			$list->setShippingAddress($address);

			$transaction = (new Transaction())
				->setItemList($list)
				->setDescription('Votre commande sur ' . $appService->getParams()->getTitle())
				->setAmount($amount)
				->setCustom($this->getUser()->getFirstname() . ' ' . $this->getUser()->getLastname());
			
			$payment = new Payment();
			$payment->setTransactions([$transaction]);
			$payment->setIntent('sale');
			$redirectUrls = (new RedirectUrls())
				//Redirection de l'utilisateur après le paiement
				->setReturnUrl($this->generateUrl('pay_paypal', [], UrlGeneratorInterface::ABSOLUTE_URL))
				//Redirection en cas d'annulation de paiement de l'utilisateur
				->setCancelUrl($this->generateUrl('cart_confirm', [], UrlGeneratorInterface::ABSOLUTE_URL));
			$payment->setRedirectUrls($redirectUrls);
			$payment->setPayer((new Payer())->setPaymentMethod('paypal'));

			try {
				$payment->create($apiContext);
				//On utilise cette ligne si on renvoie directement vrs le site de Paypal pour le paiement
				return new RedirectResponse($payment->getApprovalLink());
			} catch(PayPalConnectionException $e) {
				dump(json_decode($e->getData()));
			}
			exit();
    	} else {
    		return $this->redirectToRoute('cart');
    	}
    }
	
	/**
	 * @Route("/cart/payments/pay", name="pay_paypal")
	 */
	public function payPaypal(SessionInterface $session, MgPaypalRepository $repoPaypal, CartService $cartService)
	{
		$cart = $cartService->cart();

		$paypalIds = $repoPaypal->find(1);
		$ids = [];
		if ($paypalIds->getModeTest() == 0) {
			$ids = ['id' => $paypalIds->getUser(), 'secret' => $paypalIds->getSignature()];
		} else {
			$ids = ['id' => 'AUi8L06-YvEFAUW6PPZ90M2hu1etzJ6IQvEUvIva9veIPnHQZPBHl4Rx3-kCXGFDNWVDdEAabM85OR9n', 'secret' => 'ECuM9VXFrS1TCr61UcFutOksoPVPkjgLrx-GDWrNM2KFPdpg_VLIG2V-LuEls75JUFf-213mSzRr3bq5'];
		}
		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				$ids['id'],
				$ids['secret']
			)
		);

		//Initialisation du prix total du panier
		$totalBasketAllTaxes = $cartService->totalCart() + array_sum($cartService->getShipping());

		//Récupération du paiement Paypal
		$payment = Payment::get($_GET['paymentId'], $apiContext);

		$execution = (new PaymentExecution())
			//On utilise cette ligne si on renvoie directement vers le site de Paypal pour le paiement
			->setPayerId($_GET['PayerID'])
			//On utilise cette ligne si on fait un paiement utilisant le pop-up javascript de paypal
			//->setPayerId($_POST['payerID'])
			->setTransactions($payment->getTransactions());

		try {
			//Si le paiement Paypal est égale au montant du panier
			if ($totalBasketAllTaxes == $payment->getTransactions()[0]->getAmount()->getTotal()) {
				$payment->execute($execution, $apiContext);
				$info_transaction = serialize($payment->getTransactions()[0]);
		        if (!$session->has('info_transaction')) {
		            $session->set('info_transaction', [$info_transaction]);
		        }
				return $this->redirectToRoute('creat_order', ['mode' => 'paypal']); 
			}
		} catch(PayPalConnectionException $e) {
			return dump(json_decode($e->getData()));
		}
	}
}
