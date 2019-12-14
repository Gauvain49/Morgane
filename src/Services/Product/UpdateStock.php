<?php
namespace App\Services\Product;

use App\Entity\MgMovementsStocks;
use App\Entity\MgProducts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateStock extends AbstractController
{
	public function updateStock(MgProducts $product, $movement, $info, $quantity)
	{
		$movements = new MgMovementsStocks();
		$movements->setProduct($product);
		$movements->setMovement($movement);
		$movements->setInfo($info);
		$movements->setQuantity($quantity);
		$em = $this->getDoctrine()->getManager();
		$em->persist($movements);
	    $em->flush();
	}
}