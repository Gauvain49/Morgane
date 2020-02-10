<?php
namespace App\Services\Order;

use App\Entity\MgOrdersContent;
use App\Entity\MgOrdersTaxes;
use App\Entity\MgTaxes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaxeOrderService extends AbstractController
{
	public function createTaxeOrder(MgOrdersContent $content, int $id_taxe)
	{
		$taxe = $this->getDoctrine()->getRepository(MgTaxes::class)->find($id_taxe);
		$em = $this->getDoctrine()->getManager();
		$taxeOrder = new MgOrdersTaxes();
		$taxeOrder->setOrderContent($content);
		$taxeOrder->setTaxe($taxe);
		$taxeOrder->setBasePrice($content->getNetUnitPrice());
		$taxeOrder->setUnitTax($content->getNetUnitTax());
		$taxeOrder->setQuantity($content->getQuantity());
		$taxeOrder->setTotalTax($content->getTotalNetTax());
		//$em = $this->getDoctrine()->getManager();
		$em->persist($taxeOrder);
	    $em->flush();
	}
}