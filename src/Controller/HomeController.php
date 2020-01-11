<?php

namespace App\Controller;

use App\Repository\MgProductsImagesRepository;
use App\Repository\MgProductsRepository;
use App\Repository\MgSuppliersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(MgProductsRepository $productsRepository, MgProductsImagesRepository $productsImagesRepository, MgSuppliersRepository $suppliersRepository)
    {
        //On récupère les derniers produits
        $products = $productsRepository->catalogFront(12, 0, true);
        $suppliers = $suppliersRepository->findAll();
        $imgPath = [];
        foreach ($products as $value) {
            $image = $productsImagesRepository->findOneBy(['product' => $value->getId(), 'cover' => true]);
            
            if(!empty($image)) {
                $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::MEDIUM, 'square');
                if (!is_null($getImage)) {
                    $imgPath[$value->getId()] = $getImage;
                }
            }
        }
        return $this->render('main/index.html.twig', [
            'products' => $products,
            'imgPath' => $imgPath
        ]);
    }
}
