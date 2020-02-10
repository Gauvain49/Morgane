<?php
namespace App\Controller;

use App\Repository\MgOrdersRepository;
use App\Repository\MgProductsImagesRepository;
use App\Repository\MgProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
	/**
	 * @Route("/order/{token}", name="order_saved")
	 */
	public function orderConfirm($token, MgOrdersRepository $repoOrder, MgProductsRepository $repoProduct, MgProductsImagesRepository $productsImagesRepository)
	{
		$order = $repoOrder->findOneBy(['uniq_key' => $token]);

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

        return $this->render('main/order/order_confirm.html.twig', [
            'order' => $order,
            'nbItems' => $nbItems,
            'images' => $images
        ]);
	}
}