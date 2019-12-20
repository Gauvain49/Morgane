<?php

namespace App\Controller\Admin;

use App\Entity\MgProducts;
use App\Entity\MgProductsNumerical;
use App\Form\ProductDownloadableType;
use App\Repository\MgProductsNumericalRepository;
use App\Repository\MgProductsRepository;
use App\Services\AppService;
use App\Services\DeleteItems;
use App\Services\DownloadFileService;
use App\Services\qqFileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products/numerical")
 */
class ProductsNumericalController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="products_numerical_index", requirements={"id"="\d+"})
     */
    public function index(MgProducts $product, MgProductsRepository $repoProduct, MgProductsNumericalRepository $repoNumerical, Request $request)
    {
    	$product_numerical = $repoProduct->findOneBy(['parent' => $product, 'type' => [$product::TYPE_DOWNLOADABLE, $product::TYPE_DOWNLOADABLE_EXCLU]]);
    	$numerical = $repoNumerical->findOneByProduct($product_numerical);
        if (!is_null($numerical)) {
            $filename = $numerical->getUseFilename();
        } else {
            $filename = false;
        }
        $form = $this->createForm(ProductDownloadableType::class, $product_numerical);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($product_numerical->getNumericals() as $digital) {
                $digital->setProduct($product_numerical);
                if ($digital->getExclusive()) {
                    $product_numerical->setType($product::TYPE_DOWNLOADABLE_EXCLU);
                } else {
                    $product_numerical->setType($product::TYPE_DOWNLOADABLE);
                }
                //dump($digital->getExclusive());
                $em->persist($digital);
            }
            //dd($product_numerical->getNumericals());
            $em->persist($product_numerical);
            $em->flush();

            $this->addFlash(
                'success', 'Modification réussie !'
            );
            //Redirection
            return $this->redirectToRoute('products_numerical_index', [
                'id' => $product->getId()
                ]
            );
        }
        return $this->render('admin/products/numerical/index.html.twig', [
            'product' => $product,
            'numerical' => $numerical,
            'form' => $form->createView(),
            'filename' => $filename,
            'NavCatalogOpen' => true
        ]);
    }

    /**
     * @Route("/numerical_upload/{id}", name="products_numerical_upload", requirements={"id"="\d+"})
     */
    public function numericalUpload(MgProductsRepository $repo, Request $request)
    {
        $id = $request->query->get('id_product');
        $product = $repo->find($id);
        $product_numerical = new MgProducts();
        $numerical = new MgProductsNumerical();
        $filename_origine = $request->query->get('qqfile');
        $filename = md5(uniqid());

        $em = $this->getDoctrine()->getManager();
        //Création du nouveau produit enfant
        $product_numerical->setReference($product->getReference());
        $product_numerical->setParent($product);
        $product_numerical->setTaxe($product->getTaxe());
        $product_numerical->setSellingPrice($product->getSellingPrice());
        $product_numerical->setSellingPriceAllTaxes($product->getSellingPriceAllTaxes());
        $product_numerical->setSalesUnit(1);
        $product_numerical->setMinQuantity(1);
        $product_numerical->setSellOutOfStock(false);
        $product_numerical->setQuantity(0);
        $product_numerical->setStockManagement(false);
        $product_numerical->setPreOrder(false);
        $product_numerical->setOffline(true);
        $product_numerical->setDatePublish($product->getDatePublish());
        $product_numerical->setUser($this->getUser());
        $product_numerical->setType('downloadable');
        $em->persist($product_numerical);
        $em->flush();

        //Création de la version numérique
        $numerical->setProduct($product_numerical);
        $numerical->setFilename($filename);
        $numerical->setUseFilename($filename_origine);
        $numerical->setExclusive(false);
        //$numerical->setActivate(0);
        $em->persist($numerical);
        $em->flush();
        //On complique le nom du dossier en le hachant
        $id_numerical = hash_hmac('sha256', $numerical->getId(), 'XB240061119133vc79', false);

        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array();
        // max file size in bytes
        $sizeLimit = 10 * 1024 * 1024;
        $uploader = new qqFileUploader($filename_origine, $allowedExtensions, $sizeLimit, $filename);

        $field = $this->getParameter('upload_directory_numericals') . "/$id_numerical/";
        if(!is_dir($field)) {
            mkdir($field, 0777);
        }
        $result = $uploader->handleUpload($field);
        $_SESSION['resultat'][] = $result;
        // to pass data through iframe you will need to encode all html tags
        //echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}/{file}", name="products_download_numeric")
     */
    public function loadFileNumeric($id, $file,DownloadFileService $download)
    {
        return $download->downloadNumerical($id, $file);
    }

    /**
     * @Route("/{id}", name="products_numerical_delete", requirements={"id"="\d+"}, methods="DELETE")
     */
    public function delete(Request $request, MgProductsNumerical $numerical, MgProductsRepository $repoProducts, DeleteItems $deleteItems, AppService $app)
    {
        $id_product = $numerical->getProduct()->getId();
        $product_numerical = $repoProducts->findOneBy(['id' => $id_product, 'type' => 'downloadable']);
        $id_parent = $product_numerical->getParent()->getId();

        $filename = $numerical->getFilename();
        $field = $app->getHashHmac($numerical->getId());
        
        if ($this->isCsrfTokenValid('delete'.$numerical->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product_numerical);
            $em->remove($numerical);
            $em->flush();
            /**
             * Effacement du dossier du serveur            
             */
            $path = $this->getParameter('upload_directory_numericals') . '/' . $field;

            $deleteItems->deleteNumerical($path, $filename);
            $this->addFlash(
                'success',
                "Le format numérique est supprimé."
            );
        }

        return $this->redirectToRoute('products_numerical_index', ['id' => $id_parent]);
    }
}
