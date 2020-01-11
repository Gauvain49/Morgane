<?php

namespace App\Controller\Admin;

use App\Entity\MgPosts;
use App\Repository\MgPostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="medias_")
 */
class MediathequesController extends AbstractController
{
	/**
	 * @Route("/medias", name="index")
	 */
	public function index(MgPostsRepository $repoPosts)
	{
		$medias = $repoPosts->findByType('attachment', ['id' => 'DESC']);
		return $this->render('admin/medias/index.html.twig', [
			'medias' => $medias]);
	}

    /**
     * @Route("/{id}", name="delete", methods="DELETE")
     */
    public function delete(MgPosts $mgPost, Request $request): Response
    {
        $role = $mgPost->getType();
        $filename = $mgPost->getFilename();
        if ($this->isCsrfTokenValid('delete'.$mgPost->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mgPost);
            $em->flush();
            //Supression du fichier
            unlink($this->getParameter('upload_directory') . '/medias/' . $filename);
            if (is_file($this->getParameter('upload_directory') . '/medias/thumb/' . $filename)) {
                unlink($this->getParameter('upload_directory') . '/medias/thumb/' . $filename);
            }
            if (is_file($this->getParameter('upload_directory') . '/medias/thumb_150/' . $filename)) {
                unlink($this->getParameter('upload_directory') . '/medias/thumb_150/' . $filename);
            }
            if (is_file($this->getParameter('upload_directory') . '/medias/thumb_300/' . $filename)) {
                unlink($this->getParameter('upload_directory') . '/medias/thumb_300/' . $filename);
            }
            if (is_file($this->getParameter('upload_directory') . '/medias/thumb_850/' . $filename)) {
                unlink($this->getParameter('upload_directory') . '/medias/thumb_850/' . $filename);
            }
            //Supression du cache si c'est une image
            //$cacheManager->remove($filename);
            $this->addFlash(
                'success',
                "Supression du média réussie"
            );
        }

        return $this->redirectToRoute('medias_index');
    }
}