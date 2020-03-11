<?php

namespace App\Controller;

use App\Services\CartService;
use App\Services\Order\OrderService;
use App\Services\Order\PaymentModeOrderService;
use App\Services\Payment\ClicAndPay;
use App\Services\TokenUtils;
use Lyra\Client;
use Lyra\Tests\ClientTest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PaymentClickAndPayController extends AbstractController
{
    /**
     * @Route("cart/payment/clickandpay", name="form_clickandpay")
     */
    public function paymentClicAndPay(CartService $cartService, OrderService $orderService, PaymentModeOrderService $paymentModeOrder, TokenUtils $tokenUtils, ClicAndPay $defaultValueClient)
    {
        $defaultValueClient->getDefaultValue();
        $client = new Client();

        $cart = $cartService->cart();
        $store = array();
        //$client = new ClientTest();
        $customer = $this->getUser();
        $token = $tokenUtils->generateUniqid();
        //dd($customer->getEmail());
        $totalCart = intval($cartService->totalCart() * 100);
        //dd($totalCart);
        //La clé amount du tableau prend comme valeur un entier positif (ex: 1234 pour 12.34 EUR).
        $amount = intval(($cartService->totalCart() + array_sum($cartService->getShipping())) * 100);
        //$amount = round($amount * 100, 1);
        //dd($amount);
        $store = [
            'amount' => $amount,
            "currency" => "EUR",
            "orderId" => $token,
            "customer" => [
                'email' => $customer->getEmail()
            ]
        ];
        //dump($client->getClientEndpoint());
        $response = $client->post("V4/Charge/CreatePayment", $store);
        //dump($response);
        //$response = $client->fakePostData("V4/Charge/CreatePayment", $store);
        

        /* I check if there are some errors */
        if ($response['status'] != 'SUCCESS') {
            /* an error occurs, I throw an exception */
            //display_error($response);
            //dump($response);
            $error = $response['answer'];
            throw new \Exception("error " . $error['errorCode'] . ": " . $error['errorMessage'] );
        }

        /* everything is fine, I extract the formToken */
        $formToken = $response["answer"]["formToken"];
        
        //dump($store);
        //dump($formToken);
        //dd($cart);
        $shipping = $cartService->getShipping();

        //Initialisation du prix total du panier
        $totalBasketAllTaxes = $cartService->totalCart() + array_sum($shipping);

        return $this->render('main/payments/clickandpay.html.twig', [
            'endpoint' => $client->getClientEndpoint(),
            'publicKey' => $client->getPublicKey(),
            'formToken' => $formToken,
            'postUrlSuccess' => 'ipn_payment_clicandpay',
            'token' => $token
        ]);
    }

    /**
     * @Route("cart/payment/ipn-clicandpay", name="ipn_payment_clicandpay")
     */
    public function ipnClicAndPay(CartService $cartService, OrderService $orderService, Request $request, SessionInterface $session, ClicAndPay $defaultValueClient)
    {
        $defaultValueClient->getDefaultValue();
        $client = new Client();

        $cart = $cartService->cart();
        $store = array();
        //$client = new ClientTest();
        $customer = $this->getUser();
        $totalCart = intval($cartService->totalCart() * 100);

        //La clé amount du tableau prend comme valeur un entier positif (ex: 1234 pour 12.34 EUR).
        $amount = intval(($cartService->totalCart() + array_sum($cartService->getShipping())) * 100);

        $ipn = $request->request->all();
        $info_transaction = json_decode($ipn['kr-answer']);
        $info_transaction = serialize($info_transaction);
        if (!$session->has('info_transaction')) {
            $session->set('info_transaction', [$info_transaction]);
        }

        $transaction = json_decode($ipn['kr-answer']);

        if ($transaction->orderDetails->orderTotalAmount == $amount) {
            return $this->redirectToRoute('payment_valid', ['mode' => 'clickandpay']); 
        }
    }

    /**
     * @Route("cart/payment/ipn-clicandpay-test", name="ipn_payment_clicandpay-test")
     */
    public function ipnClicAndPayTest(CartService $cartService, OrderService $orderService, Request $request, SessionInterface $session, ClicAndPay $defaultValueClient)
    {
        $defaultValueClient->getDefaultValue();
        $client = new Client();

        $cart = $cartService->cart();
        $store = array();
        //$client = new ClientTest();
        $customer = $this->getUser();
        $totalCart = intval($cartService->totalCart() * 100);

        //La clé amount du tableau prend comme valeur un entier positif (ex: 1234 pour 12.34 EUR).
        $amount = intval(($cartService->totalCart() + array_sum($cartService->getShipping())) * 100);

        $ipn = $request->request->all();
        $info_transaction = json_decode($ipn['kr-answer']);
        $info_transaction = serialize($info_transaction);
        if (!$session->has('infoTransClickAndPay')) {
            $session->set('infoTransClickAndPay', [$info_transaction]);
        }

        $transaction = json_decode($ipn['kr-answer']);

        if ($transaction->orderDetails->orderTotalAmount == $amount) {
            return $this->redirectToRoute('payment_valid', ['mode' => 'clickandpay']); 
        }
    }
}