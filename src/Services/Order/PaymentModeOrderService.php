<?php
namespace App\Services\Order;

use App\Entity\MgOrders;
use App\Entity\MgOrdersPayments;
use App\Entity\MgPaymentsModes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentModeOrderService extends AbstractController
{
	public function createPaymentModeOrder(MgOrders $order, $modePayment, $paymentAmount, $infoTransaction = null)
	{
		//$mode = $this->getDoctrine()->getRepository(MgPaymentsModes::class)->find($modeId);
		$payment = new MgOrdersPayments();
		$payment->setPaymentOrder($order);
		$payment->setPaymentMode($modePayment);
		$payment->setPaymentAmount($paymentAmount);
		$payment->setInfoTransaction($infoTransaction);

		$em = $this->getDoctrine()->getManager();
		$em->persist($payment);
	    $em->flush();

	    return $payment;
	}
}