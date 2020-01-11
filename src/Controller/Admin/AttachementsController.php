<?php

namespace App\Controller\Admin;

use App\Entity\MgPosts;
use App\Repository\MgPostsRepository;
use App\Services\MimeType;
use App\Services\ResizeImg;
use App\Services\qqFileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AttachementsController extends AbstractController
{
    /**
     * @Route("/admin/attachements/editorUpload", name="medias_editorUpload")
     */
	public function quickUpload(MgPostsRepository $repoPosts, Request $request): Response
	{
		$attachements = $repoPosts->findBy(['type' => 'attachment'], ['id' => 'DESC']);

		return $this->render('admin/attachements/editorUpload.html.twig', [
			'attachements' => $attachements
		]);
	}

    /**
     * @Route("/admin/attachements/media_upload", name="medias_upload", methods={"GET","POST"})
     */
    public function mediaUpload(ResizeImg $resize, Request $request)
    {
        //$productImg = new MgProductsImages;
        $mimeType = new MimeType();
        //dump($request->request->get('qqfile'));

        // liste des extensions valides, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array();
        // taille max du fichier en bytes
        $sizeLimit = 10 * 1024 * 1024;
        $uploader = new qqFileUploader(null, $allowedExtensions, $sizeLimit);
        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
        $field = $this->getParameter('upload_directory') . '/medias/';
        $result = $uploader->handleUpload($field);
        $_SESSION['resultat'][] = $result;

        if (!array_key_exists('error', $result)) {
            $filename = $result['newFilename'];

            $size = getimagesize($field . $filename);
            //Si le media est une image, on peut le redimensionner
            if ($mimeType->getExtensionResized($size['mime'])) {
                //Création de la vignette
                $resize->resizeImg(
                    $field . $filename,
                    $field . 'thumb/' . $filename,
                    trim($mimeType->getExtension($size['mime']), '.'),
                    200,
                    true);
                if ($size[0] > 150) {
                    //resizeImg($img, $img_dest, $ext, $max, $square = false)
                    $resize->resizeImg(
                        $field . $filename,
                        $field . 'thumb_150/' . $filename,
                        trim($mimeType->getExtension($size['mime']), '.'),
                        150);
                }
                if ($size[0] > 300) {
                    //resizeImg($img, $img_dest, $ext, $max, $square = false)
                    $resize->resizeImg(
                        $field . $filename,
                        $field . 'thumb_300/' . $filename,
                        trim($mimeType->getExtension($size['mime']), '.'),
                        300);
                }
                if ($size[0] > 850) {
                    //resizeImg($img, $img_dest, $ext, $max, $square = false)
                    $resize->resizeImg(
                        $field . $filename,
                        $field . 'thumb_850/' . $filename,
                        trim($mimeType->getExtension($size['mime']), '.'),
                        850);
                }
            }
        }
        $media = new MgPosts;
        $em = $this->getDoctrine()->getManager();
        $media->setUser($this->getUser());
        $media->setStatus('publish');
        $media->setType($media::ATTACHMENT);
        $media->setMimeType($size['mime']);
        $media->setSizes(serialize($size));
        $media->setPosition(0);
        $media->setComment(false);
        $media->setFilename($filename);
        $media->setDatePublish(new \Datetime());
        $em->persist($media);
        $em->flush();
        // to pass data through iframe you will need to encode all html tags
        //echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        return new JsonResponse($result);
    }
}