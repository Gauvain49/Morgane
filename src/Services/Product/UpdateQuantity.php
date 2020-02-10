<?php
namespace App\Services\Product;

use App\Entity\MgMovementsStocks;
use App\Entity\MgProducts;
use App\Services\Product\UpdateStock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateQuantity extends AbstractController
{
	protected $stock;

	public function __construct(UpdateStock $stock)
	{
		$this->stock = $stock;
	}

	public function updateQuantity(MgProducts $product, $quantity, $orderNum)
	{
		$p = $this->getDoctrine()->getRepository(MgProducts::class)->find($product->getId());
	    $p->setQuantity($product->getQuantity() - $quantity);
		$em = $this->getDoctrine()->getManager();
		$em->persist($p);
	    $em->flush();

	    $info = 'Commande numéro ' . $orderNum;

	    $this->stock->updateStock($p, ($quantity * -1), $info, $p->getQuantity());
	}
}