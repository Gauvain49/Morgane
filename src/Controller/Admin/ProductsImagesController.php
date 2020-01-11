<?php

namespace App\Controller\Admin;


use App\Entity\MgProducts;
use App\Entity\MgProductsImages;
use App\Repository\MgProductsImagesRepository;
use App\Repository\MgProductsRepository;
use App\Services\DeleteItems;
use App\Services\MimeType;
use App\Services\ResizeImg;
use App\Services\qqFileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products")
 */
class ProductsImagesController extends AbstractController
{
    /**
     * @Route("/{id}/images", name="images_index")
     */
    public function index(Request $request, MgProducts $product, MgProductsImagesRepository $repoImages, MimeType $mimeType)
    {
    	$images = $repoImages->findByProduct($product->getId());
        //dump($images);
        $imgPath = [];
        foreach ($images as $value) {
            $imgPath[$value->getId()] = $repoImages->getImgBySize($this->getParameter('upload_directory_products') . '/', $value->getId(), $repoImages::SMALL, 'square');
        }
        return $this->render('admin/products/images/index.html.twig', [
            'product' => $product,
            'id' => $product->getId(),
            'images' => $images,
            'imgPath' => $imgPath,
            'NavCatalogOpen' => true
        ]);
    }

    /**
     * @Route("/{id}/images/image_upload", name="images_upload", requirements={"id"="\d+"})
     */
    public function imageUpload(MgProductsRepository $repo, MgProductsImagesRepository $repoImages, ResizeImg $resize, Request $request)
    {
        $id = $request->query->get('id_product');
        $product = $repo->find($id);
        $productImg = new MgProductsImages;

        $em = $this->getDoctrine()->getManager();
        $productImg->setProduct($product);

        //Pour définir une position, On vérifie si au moins une image existe déjà
        $ifProductExist = $repoImages->findOneByProduct($product, ['position' => 'DESC']);
        //Si aucune image n'existe, on la met en cover et en position 1
        if (empty($ifProductExist)) {
            $productImg->setCover(true);
            $productImg->setPosition(1);
        } else {
            $productImg->setCover(false);
            $productImg->setPosition($ifProductExist->getPosition()+1);
        }
        //On définie d'abord un type mime par défaut
        $productImg->setMimeType('image/jpeg');
        $em->persist($productImg);
        $em->flush();
        $id_image = $productImg->getId();

        // liste des extensions valides, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array('jpg', 'jpeg', 'gif', 'png');
        // taille max du fichier en bytes
        $sizeLimit = 10 * 1024 * 1024;
        $uploader = new qqFileUploader($id_image, $allowedExtensions, $sizeLimit);
        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
        if(strlen($id_image) == 1) {
            $field = $this->getParameter('upload_directory_products') . "/$id_image/";
            if(!is_dir($field)) {
                mkdir($field, 0777);
            }
        } else {
            $field = $this->getParameter('upload_directory_products') . '/';
            $splitId = str_split($id_image);
            foreach($splitId as $split) {
                $field .= $split . '/';
                if(!is_dir($field)) {
                    mkdir($field, 0777, true);
                }
            }
        }
        $result = $uploader->handleUpload($field);
        $_SESSION['resultat'][] = $result;
        if (!array_key_exists('error', $result)) {
            //On récupère et donne ensuite le vrai type mime
            $size = getimagesize($field . $result['newFilename']);
            $productImg->setMimeType($size['mime']);
            $em->persist($productImg);
            $em->flush();
        } else {
            $em->remove($productImg);
            $em->flush();
        }
        // to pass data through iframe you will need to encode all html tags
        //echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        return new JsonResponse($result);
    }

    /**
     * @Route("{id}/images_cover/", name="images_cover", methods="GET")
     */
    public function coverImage(Request $request, MgProductsImages $image, MgProductsImagesRepository $repoImg)
    {
        $id_product = $image->getProduct()->getId();
        //On récupère l'image déjà en principale
        $coverImage = $repoImg->findOneBy(['product' => $image->getProduct()->getId(), 'cover' => '1']);
        //Puis on lui retire cette propriété
        $em = $this->getDoctrine()->getManager();
        $coverImage->setCover(false);
        $em->persist($coverImage);
        $em->flush();

        //On ajoute ensuite cette propriété à l'image sélectionnée
        $image->setCover(true);
        $em->persist($image);
        $em->flush();

        return $this->redirectToRoute('images_index', ['id' => $id_product]);
    }

    /**
     * @Route("/images/product-image-dell/{id}", name="images_delete", methods="DELETE")
     */
    public function deleteImage(Request $request, MgProductsImages $image, MgProductsImagesRepository $repoImg, DeleteItems $deleteItems)
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $id = $image->getId();
            $id_product = $image->getProduct()->getId();
            //On vérifie d'abord si l'image n'est pas celle designée en couverture...
            //... Si c'est le cas, on lui retire cette propriété et on l'attribue la première image de la liste
            if ($image->getCover()) {
                $image->setCover(false);
                $em->persist($image);
                $em->flush($image);
                $nextImage = $repoImg->findOneByNotId($image->getId(), $image->getProduct());
                if (!empty($nextImage)) {
                    $nextImage->setCover(true);
                    $em->persist($nextImage);
                    $em->flush($nextImage);
                }
            }
            $em->remove($image);
            $em->flush();
            /**
             * Effacement de l'image du serveur            
             */
            //On récupère le chemin du dossier
            $path = $this->getParameter('upload_directory_products') . '/';
            $fields = str_split($id);
            foreach($fields as $field) {
                $path .= $field . '/';
            }
            //... on vide ce qu'il contient, puis on le détruit.
            $deleteItems->deleteDirectoryAndHisFiles($path);

            $this->addFlash(
                'success',
                "L'image a bien été supprimée."
            );
        }
        return $this->redirectToRoute('images_index', ['id' => $id_product]);
    }
}