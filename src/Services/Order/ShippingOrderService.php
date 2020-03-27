<?php
namespace App\Services\Order;

use App\Entity\MgOrdersCarriers;
use App\Repository\MgCarriersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShippingOrderService extends AbstractController
{
	protected $carriers;

	public function __construct(MgCarriersRepository $carriers)
	{
		$this->carriers = $carriers;
	}
	/**
	 * Insertion des frais de port dans la base
	 */
	public function creatShippingOrder($shipping, $order)
	{
		$orderShipping = new MgOrdersCarriers();
		if (!empty($shipping)) {
			$em = $this->getDoctrine()->getManager();
			foreach ($shipping as $key => $value) {
				$carrier = $this->carriers->find($key);
				$orderShipping->setGetOrder($order);
				$orderShipping->setCarrier($carrier);
				$orderShipping->setShippingCostTaxExcl($value['price']);
				$orderShipping->setShippingCostTaxes($value['taxes']);
				$em->persist($orderShipping);
			}
			$em->flush();
		}
	}
}