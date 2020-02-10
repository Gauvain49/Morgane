<?php
namespace App\Services\Order;

use App\Entity\MgOrders;
use App\Entity\MgOrdersStatus;
use App\Entity\MgOrdersStatusLang;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatusOrderService extends AbstractController
{
	public function createStatusOrder(MgOrders $orders, $mode)
	{
		//Récupération du status
		if ($mode == 'paypal') {
			$status = $this->getDoctrine()->getRepository(MgOrdersStatusLang::class)->find(14);
		} elseif ($mode == 'check') {
			$status = $this->getDoctrine()->getRepository(MgOrdersStatusLang::class)->find(1);
		} elseif ($mode == 'clickandpay') {
			$status = $this->getDoctrine()->getRepository(MgOrdersStatusLang::class)->find(11);
		} else {
			$status = $this->getDoctrine()->getRepository(MgOrdersStatusLang::class)->find(14);
		}
		$statusPayment = new MgOrdersStatus();

		$statusPayment->setStatusOrder($orders);
		$statusPayment->setStatus($status);

		$em = $this->getDoctrine()->getManager();
		$em->persist($statusPayment);
	    $em->flush();
	}
}