<?php

namespace App\Controller\Admin;

use App\Entity\MgOrders;
use App\Entity\MgOrdersPayments;
use App\Entity\MgOrdersStatus;
use App\Form\OrdersPaymentsType;
use App\Form\OrdersStatusType;
use App\Repository\MgOrdersPaymentsRepository;
use App\Repository\MgOrdersRepository;
use App\Repository\MgOrdersStatusLangRepository;
use App\Repository\MgOrdersStatusRepository;
use App\Repository\MgPaymentsModesRepository;
use App\Repository\MgProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    /**
     * @Route("/admin/orders", name="orders_index")
     */
    public function index(MgOrdersRepository $repoOrders, MgOrdersPaymentsRepository $repoPayment, MgPaymentsModesRepository $repoModePayment)
    {
    	$orders = $repoOrders->findBy([], ['id' => 'DESC']);
    	$modePayment = [];
    	foreach ($orders as $value) {
    		$payment = $repoPayment->findOneBy(['payment_order' => $value], ['id' => 'DESC']);
    		if (!is_null($payment)) {
    			$modePayment[$value->getId()] = $repoModePayment->find($payment->getPaymentMode()->getId());
    		}
    	}
        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders,
            'payment' => $modePayment
        ]);
    }

    /**
     * @Route("/admin/order/{id}/edit", name="order_edit")
     */
    public function edit(Request $request, MgOrders $order, MgOrdersPaymentsRepository $repoPayment, MgOrdersStatusRepository $repoStatus, MgOrdersStatusLangRepository $repoStatusLang, MgPaymentsModesRepository $repoModePayment, MgProductsRepository $repoProduct)
    {
    	$product = [];
    	foreach ($order->getOrdersContents() as $content) {
    		$product[$content->getId()] = $repoProduct->find($content->getProduct());
    	}
        $orderStatus = new MgOrdersStatus();
        $orderPayments = new MgOrdersPayments();
        $payment = $repoPayment->findOneBy(['payment_order' => $order], ['id' => 'DESC']);
        $modePayment = $repoModePayment->find($payment->getPaymentMode()->getId());
        $status = $repoStatus->findBy(['status_order' => $order], ['id' => 'DESC']);
        $payments = $repoPayment->findBy(['payment_order' => $order], ['id' => 'DESC']);
        //On met dans un tableau les paiments qui ont été annulé, pour éviter qu'il le soit à nouveau
        $paymentDeleted = [];
    	$typePayment = [];
        foreach ($payments as $value) {
            if (!is_null($value->getPaymentParent())) {
                $paymentDeleted[$value->getPaymentParent()->getId()] = $value->getId();
            }
            $typePayment[$value->getId()] = $repoModePayment->findOneById($value->getPaymentMode()->getId());
        }
        $form = $this->createForm(OrdersStatusType::class, $orderStatus);
        $form_payments = $this->createForm(OrdersPaymentsType::class, $orderPayments);
        $form->handleRequest($request);
        $form_payments->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*$task = $form->getData();*/
            $orderStatus->setStatusOrder($order);
            $em = $this->getDoctrine()->getManager();
            $em->persist($orderStatus);
            $em->flush();
            $order->setCurrentStatus($repoStatusLang->find($orderStatus->getStatus()));
            $em->persist($order);
            $em->flush();
            $this->addFlash(
                'success',
                "Modification réussie !"
            );

            return $this->redirectToRoute('order_edit', ['id' => $order->getId()]);
        }

        if ($form_payments->isSubmitted() && $form_payments->isValid()) {
            $task = $form_payments->getData();
            /*dump($task);*/
            $orderPayments->setPaymentOrder($order);
            $em = $this->getDoctrine()->getManager();
            $em->persist($orderPayments);
            $em->flush();
            $this->addFlash(
                'success',
                "Modification réussie !"
            );

            return $this->redirectToRoute('order_edit', ['id' => $order->getId()]);
        }
    	return $this->render('admin/orders/edit.html.twig', [
    		'order' => $order,
    		'payment' => $payment,
            'payments' => $payments,
            'modePayment' => $modePayment,
    		'status' => $status,
            'form' => $form->createView(),
            'form_payments' => $form_payments->createView(),
            'paymentDeleted' => $paymentDeleted,
            'product' => $product,
            'typePayment' => $typePayment
    	]);
    }

    /**
     * Annuler un paiement associé à une commande
     * (Le paiement n'est pas supprimé)
     *
     * @Route("/admin/order/payment/{id}/delete", name="payment_order_edit")
     */
    public function dellPaymentOrder(MgOrdersPayments $order_payment)
    {
        $payment_dell = new MgOrdersPayments();
        $payment_dell->setPaymentOrder($order_payment->getPaymentOrder());
        $payment_dell->setPaymentMode($order_payment->getPaymentMode());
        //$payment_dell->setPaymentType($order_payment->getPaymentType());
        $payment_dell->setPaymentAmount($order_payment->getPaymentAmount() * -1);
        $payment_dell->setPaymentParent($order_payment);

        $em = $this->getDoctrine()->getManager();
        $em->persist($payment_dell);
        $em->flush();
            $this->addFlash(
                'success',
                "Le paiement a été annulé avec succès."
            );

            return $this->redirectToRoute('order_edit', ['id' => $order_payment->getPaymentOrder()->getId()]);
    }
}