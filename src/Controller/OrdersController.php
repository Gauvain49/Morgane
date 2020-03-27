<?php
namespace App\Controller;

use App\Entity\MgOrders;
use App\Entity\MgOrdersContent;
use App\Entity\MgOrdersPayments;
use App\Repository\MgOrdersRepository;
use App\Repository\MgPaymentsModesRepository;
use App\Repository\MgProductsImagesRepository;
use App\Repository\MgProductsRepository;
use App\Services\AppService;
use App\Services\CartService;
use App\Services\Order\OrderService;
use App\Services\Order\PaymentModeOrderService;
use App\Services\Order\ShippingOrderService;
use App\Services\SendEmails;
use App\Services\ShippingService;
use App\Services\TokenUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{

    /**
     * @Route("/order/creat_order/{mode}", name="creat_order")
     */
    public function newOrder($mode, CartService $cartService, ShippingService $shippingService, OrderService $orderService, ShippingOrderService $shippingOrderService, PaymentModeOrderService $paymentModeOrder, TokenUtils $tokenUtils, SessionInterface $session, MgPaymentsModesRepository $repoPayment, AppService $appService, SendEmails $sendEmails)
    {
        $cart = $cartService->cart();
        $shipping = current($session->get('shipping'));
        $totalShipping = $shippingService->totalCostShipping();
        if (!$session->has('info_transaction')) {
            $session->set('info_transaction', []);
        }

        $info_transaction = $session->get('info_transaction');
        $info_transaction = current($info_transaction);

        //Initialisation du prix total du panier
        $totalBasketAllTaxes = $cartService->totalCart() + array_sum($totalShipping);
        $modePayment = $repoPayment->findOneBy(['type' => $mode]);

        //Création de la commande
        $session->set('numOrder', $tokenUtils->generateToken(11));
        $numOrder = $session->get('numOrder');
        $newOrder = $orderService->creatOrder($this->getUser(), $mode, $numOrder);
        $newPayment = $paymentModeOrder->createPaymentModeOrder($newOrder, $modePayment, $totalBasketAllTaxes, $info_transaction);
        $newShipping = $shippingOrderService->creatShippingOrder($shipping, $newOrder);

        $session->remove('info_transaction');
        $session->remove('cart');
        $session->remove('shipping');
        $session->remove('numOrder');

        /**
         * Envoi des emails avertissant/confirmant la commande
         */
        //$appService->getParams()->getTitle();

        //Template des emails au format html et texte
        /*$template = $this->forward('App\Controller\OrdersController::orderConfirmEmail', [
            'token' => $newOrder->getUniqKey()
        ]);*/
        $template = $this->orderConfirmEmail($newOrder->getId());
        /*$templateTxt = $this->forward('App\Controller\OrdersController::orderConfirmEmailTxt', [
            'token' => $newOrder->getUniqKey()
        ]);*/
        $templateTxt = $this->orderConfirmEmailTxt($newOrder->getId());
        //On ne récupère que le content du template, sinon il affiche les headers dans l'en-tête du mail
        $template = $template->getContent();
        $templateTxt = $templateTxt->getContent();

        //Envoi au client
        $sendMailCustomer = $sendEmails->confirmOrder($template, $templateTxt, $newOrder->getNum(), $appService->getParams()->getEmailOrder(), $appService->getParams()->getTitle(), $this->getUser()->getEmail(), $this->getUser()->getCompleteName());

        //Envoi à 'administrateur du site'
        $sendMailAdmin = $sendEmails->confirmOrder($template, $templateTxt, $newOrder->getNum(), $appService->getParams()->getEmailOrder(), $appService->getParams()->getTitle(), $appService->getParams()->getEmailOrder(), $appService->getParams()->getTitle());

        return $this->redirectToRoute('order_saved');
    }

	/**
	 * @Route("/order", name="order_saved")
     * @IsGranted("ROLE_VISITOR", message="Vous n'avez pas les autorisations pour créer des comptes.")
	 */
	public function orderConfirm(MgOrdersRepository $repoOrder, MgProductsRepository $repoProduct, MgProductsImagesRepository $productsImagesRepository, SessionInterface $session)
	{
        $numOrder = $session->get('numOrder');
        if (!is_null($numOrder)) {
            $order = $repoOrder->findOneBy(['num' => $numOrder]);
        } else {
            $order = $repoOrder->findOneBy(['user' => $this->getUser()], ['id' => 'DESC']);
        }
        if (!is_null($order)) {
            //Nombre d'article à livrer et/ou télécharger
            $nbItems = ['master' => 0, 'downloadable' => 0];
            //Récupération des images
            $images = [];
            foreach ($order->getOrdersContents() as $value) {
                if ($value->getFormat() == 'downloadable') {
                    $nbItems['downloadable'] += $value->getQuantity();
                } else {
                    $nbItems['master'] += $value->getQuantity();
                }

                $image = $productsImagesRepository->findOneBy(['product' => $value->getProduct(), 'cover' => true]);
                if(!empty($image)) {
                    $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::SMALL, 'square');
                    if (!is_null($getImage)) {
                        $images[$value->getProduct()] = $getImage;
                    }
                }
            }            
        } else {
            $order = $nbItems = $images = false;
        }


        return $this->render('main/order/order_confirm.html.twig', [
            'order' => $order,
            'nbItems' => $nbItems,
            'images' => $images
        ]);
	}

    /**
     * @Route("/email/{id}", name="send_email_confirm_order")
     */
    public function orderConfirmEmail($id)
    {
        $repoOrder = $this->getDoctrine()->getRepository(MgOrders::class);
        $repoContent = $this->getDoctrine()->getRepository(MgOrdersContent::class);
        $repoPayment = $this->getDoctrine()->getRepository(MgOrdersPayments::class);
        $order = $repoOrder->findOneBy(['id' => $id]);
        $contents = $repoContent->findBy(['get_order' => $order]);
        $payments = $repoPayment->findOneBy(['payment_order' => $order]);

        $template =  $this->renderView('main/order/emails/confirm_order.html.twig', [
            'order' => $order,
            'payments' => $payments,
            'contents' => $contents
        ]);

        return new Response($template);
    }

    /**
     * @Route("/email/{id}", name="send_email_confirm_order")
     */
    public function orderConfirmEmailTxt($id)
    {
        $repoOrder = $this->getDoctrine()->getRepository(MgOrders::class);
        $repoContent = $this->getDoctrine()->getRepository(MgOrdersContent::class);
        $repoPayment = $this->getDoctrine()->getRepository(MgOrdersPayments::class);
        $order = $repoOrder->findOneBy(['id' => $id]);
        $contents = $repoContent->findBy(['get_order' => $order]);
        $payments = $repoPayment->findOneBy(['payment_order' => $order]);

        $template =  $this->renderView('main/order/emails/confirm_order.txt.twig', [
            'order' => $order,
            'payments' => $payments,
            'contents' => $contents
        ]);

        return new Response($template);
    }
}