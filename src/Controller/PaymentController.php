<?php

namespace App\Controller;

use App\Services\CartService;
use App\Services\Order\OrderService;
use App\Services\Order\PaymentModeOrderService;
use App\Services\TokenUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route("cart/payment/valid/{mode}", name="payment_valid")
     */
    public function validPaymentAndOrder($mode, CartService $cartService, OrderService $orderService, PaymentModeOrderService $paymentModeOrder, TokenUtils $tokenUtils, SessionInterface $session)
    {
        if ($mode == 'check') {
            return $this->redirectToRoute('creat_order', ['mode' => 'check']);
        } elseif ($mode == 'paypal') {
            return $this->redirectToRoute('payment_paypal');
        } elseif ($mode == 'clic_and_pay') {
            return $this->redirectToRoute('form_clickandpay');
        }
    }
    
	/**
	 * @Route("cart/payment/check", name="payment_check")
	 */
	public function paymentCheck(CartService $cartService, OrderService $orderService, PaymentModeOrderService $paymentModeOrder, TokenUtils $tokenUtils, SessionInterface $session)
	{
		$cart = $cartService->cart();
		$shipping = $cartService->getShipping();

        if (!$session->has('infoTransClickAndPay')) {
            $session->set('infoTransClickAndPay', []);
        }

        $infoTransClickAndPay = $session->get('infoTransClickAndPay');
        $infoTransClickAndPay = current($infoTransClickAndPay);

        //Initialisation du prix total du panier
        $totalBasketAllTaxes = $cartService->totalCart() + array_sum($shipping);

        //Création de la commande
        $session->set('numOrder', $tokenUtils->generateToken(11));
        $numOrder = $session->get('numOrder');
        $newOrder = $orderService->creatOrder($this->getUser(), 'check', $numOrder);
        $newPayment = $paymentModeOrder->createPaymentModeOrder($newOrder, 2, 'Check', $totalBasketAllTaxes);

        $session->remove('infoTransClickAndPay');
        $session->remove('cart');

        return $this->redirectToRoute('order_saved');
	}

    /**
     * @Route("cart/payment/paymentClickAndPay", name="payment_clickandpay", methods={"GET","POST"})
     */
    public function paymentClickAndPay(CartService $cartService, OrderService $orderService, PaymentModeOrderService $paymentModeOrder, TokenUtils $tokenUtils, SessionInterface $session)
    {
        $cart = $cartService->cart();
        $shipping = $cartService->getShipping();

        if (!$session->has('infoTransClickAndPay')) {
            $session->set('infoTransClickAndPay', []);
        }

        $infoTransClickAndPay = $session->get('infoTransClickAndPay');
        $infoTransClickAndPay = current($infoTransClickAndPay);

        //Initialisation du prix total du panier
        $totalBasketAllTaxes = $cartService->totalCart() + array_sum($shipping);

        //Création de la commande
        $session->set('numOrder', $tokenUtils->generateToken(11));
        $numOrder = $session->get('numOrder');
        $newOrder = $orderService->creatOrder($this->getUser(), 'clickandpay', $numOrder);
        $newPayment = $paymentModeOrder->createPaymentModeOrder($newOrder, 4, 'Check', $totalBasketAllTaxes, $infoTransClickAndPay);

        $session->remove('infoTransClickAndPay');
        $session->remove('cart');

        return $this->redirectToRoute('order_saved');
    }
}