<?php

namespace App\Controller;
use App\Entity\MgProducts;
use App\Repository\MgCategoriesRepository;
use App\Repository\MgProductsImagesRepository;
use App\Repository\MgProductsLangRepository;
use App\Repository\MgProductsRepository;
use App\Repository\MgPropertiesRepository;
use App\Repository\MgSuppliersRepository;
use App\Repository\MgTaxesRepository;
use App\Services\Languages;
use App\Services\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/c/{slug}/{page<\d+>?1}", name="catalog")
     */
    public function catalog($slug = false, $page, MgCategoriesRepository $repoCat, MgSuppliersRepository $repoSuppliers, MgProductsRepository $repoProduct, MgProductsImagesRepository $productsImagesRepository, MgPropertiesRepository $repoProperties, Languages $languages, Pagination $pagination)
    {
        $pagination->setEntityClass(MgProducts::class)
                    ->setPage($page)
                    ->setLimit(12);
        $filAriane = [];
        if ($slug) {
            $category = $repoCat->getOneBySlug($slug, $languages->languageDefault());
            //Construction de la pagination
            $productIds = '';
            foreach ($category->getProducts() as $p) {
                $productIds .= $p->getId() . ', ';
            }
            if (empty($productIds)) {
                $productIds = 0;
            }
            //dd($productIds);
            $productIds = trim($productIds, ', ');
            $countProduct = count($repoProduct->getProductByIds($productIds));
            //dd($countProduct);
            $pages = $pagination->getPages($countProduct);
            $products = $repoProduct->getProductByIds($productIds, $pagination->getLimit(), $pagination->getOffset());
            //Récupération des images
            $imgPath = [];
            foreach ($products as $product) {
                $image = $productsImagesRepository->findOneBy(['product' => $product->getId(), 'cover' => true]);
                if(!empty($image)) {
                    $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::MEDIUM, 'square');
                    if (!is_null($getImage)) {
                        $imgPath[$product->getId()] = $getImage;
                    }
                }
            }
            //Création du fil d'Ariane
            $parentID = $category->getId();
            while ($parentID != 1) {
                $getParent = $repoCat->findOneBy(['id' => $parentID]);
                foreach ($getParent->getContents() as $v) {
                    if ($v->getLang() == $languages->languageDefault()) {
                        $filAriane[$parentID] = ['name' => $v->getName(), 'slug' => $v->getSlug()];
                    }
                }
                $parentID = $getParent->getParent()->getId();
            }

            //On récupère les enfants (de premier niveau) de la catégorie
            $categoriesChildren = $repoCat->findBy(['type' => 'product', 'parent' => $category], ['position'=>'ASC']);

            //On récupère la liste de propriété
            $properties = $repoProperties->findAll();

        } else {
        }
        $filAriane = array_reverse($filAriane, true);
        //Les fournisseurs
        $suppliers = $repoSuppliers->findAll();


        return $this->render('main/catalog.html.twig', [
            'products' => $products,
            'catTitle' => $category->getContents()[0]->getName(),
            'filAriane' => $filAriane,
            'categoriesChildren' => $categoriesChildren,
            'suppliers' => $suppliers,
            'imgPath' => $imgPath,
            'page' => $page,
            'pages' => $pages,
            'slugCat' => $slug,
            'properties' => $properties,
            'pagination' => $countProduct > $pagination->getLimit() ? true : false
            /*'discountFrom' => $discountFrom,
            'listes' => $listes,
            'listesOneChild' => $listesOneChild,
            'url' => $url,
            'getCat' => $cat,
            'totalProduct' => $totalProduct,
            'start' => $pagination->getOffset() + 1,
            'end' => $pagination->getOffset() + count($products)*/
        ]);
    }
	/**
	 * @Route("/p/{slug}", name="product_detail")
	 */
	public function show($slug, Languages $languages, MgProductsLangRepository $repoContent, MgProductsImagesRepository $productsImagesRepository, MgProductsRepository $repoProduct, MgSuppliersRepository $repoSuppliers, MgTaxesRepository $repoTaxes)
	{
		$content = $repoContent->findOneBy(['slug' => $slug, 'lang' => $languages->languageDefault()]);
        //$product = $repoProduct->find($content->getProduct($content->getProduct()->getId()));
        $product = $repoProduct->find($content->getProduct());
        $imgCover = '';
        $imgSecondary = [];
        $imgSecondaryOrigin = [];
        $imgCoverOrigin = '';
        $image = $productsImagesRepository->findOneBy(['product' => $product->getId(), 'cover' => true]);
            
        if(!empty($image)) {
            $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::LARGE);
            if (!is_null($getImage)) {
                $imgCover = $getImage;
            }
            $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::ORIGINE, '');
            if (!is_null($getImage)) {
                $imgCoverOrigin = $getImage;
            }
        }

        $images = $productsImagesRepository->findBy(['product' => $product->getId(), 'cover' => false]);
        if(!empty($images)) {
            foreach ($images as $image) {
                $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::SMALL, 'square');
                if (!is_null($getImage)) {
                    $imgSecondary[] = $getImage;
                }
                $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::ORIGINE, '');
                if (!is_null($getImage)) {
                    $imgSecondaryOrigin[] = $getImage;
                }
            }
        }
        $taxe = $repoTaxes->find($product->getTaxe());
        $numerical = $repoProduct->findOneBy(['parent' => $product, 'offline' => 0, 'type' => 'downloadable']);
        //dd($product->getSupplier());
        if (!is_null($product->getSupplier())) {
            $supplier = $repoSuppliers->find($product->getSupplier());
        } else {
            $supplier = '';
        }
        return $this->render('main/product.html.twig', [
            'content' => $content,
            'product' => $product,
            'imgCover' => $imgCover,
            'imgCoverOrigin' => $imgCoverOrigin,
            'imgSecondary' => $imgSecondary,
            'imgSecondaryOrigin' => $imgSecondaryOrigin,
            'taxe' => $taxe,
            'numerical' => $numerical,
            'supplier' => $supplier
        ]);

	}
}