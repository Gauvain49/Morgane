<?php

namespace App\Controller\Admin;

use App\Entity\MgAuthors;
use App\Form\AuthorsType;
use App\Repository\MgAuthorsRepository;
use App\Services\DeleteItems;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/authors")
 */
class AuthorsController extends AbstractController
{
    /**
     * @Route("/", name="authors_index", methods={"GET"})
     */
    public function index(MgAuthorsRepository $mgAuthorsRepository): Response
    {
        return $this->render('admin/authors/index.html.twig', [
            'authors' => $mgAuthorsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="authors_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $author = new MgAuthors();
        $form = $this->createForm(AuthorsType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $file = $author->getImg();
            $entityManager->persist($author);
            $entityManager->flush();
            if (!is_null($file)) {
                $author->setImg($file->getClientOriginalName());
                $path = $this->getParameter('upload_directory') . '/authors/' . $author->getId();
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $filename = $file->getClientOriginalName();
                try {
                    $file->move($path, $filename);
                } catch (FileException $e) {
                    die($e);
                }
            }
            $entityManager->persist($author);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "Ajout du nouvel auteur réussi."
            );

            return $this->redirectToRoute('authors_index');
        }

        return $this->render('admin/authors/new.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="authors_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgAuthors $author, DeleteItems $deleteItems): Response
    {
        $img = $author->getImg();
        $form = $this->createForm(AuthorsType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            //dd($request->get('dellImg'));
            $entityManager = $this->getDoctrine()->getManager();
            $file = $task->getImg();
            if ($request->get('dellImg') == 'on') {
                //Si une image existe déjà, on la supprime
                if(!is_null($img)) {
                    $deleteItems->deleteDirectoryAndHisFiles($this->getParameter('upload_directory') . '/authors/' . $author->getId() . '/');
                }
            } else {
                //S'il existe une image
                if (!is_null($file)) {
                    //Si une image existe déjà, on la supprime
                    if(!is_null($img)) {
                        $deleteItems->deleteDirectoryAndHisFiles($this->getParameter('upload_directory') . '/authors/' . $author->getId() . '/');
                    }
                    //On charge l'image sélectionné
                    $author->setImg($file->getClientOriginalName());
                    $path = $this->getParameter('upload_directory') . '/authors/' . $author->getId();
                    if (!is_dir($path)) {
                        mkdir($path, 0777, true);
                    }
                    $filename = $file->getClientOriginalName();
                    try {
                        $file->move($path, $filename);
                    } catch (FileException $e) {
                        die($e);
                    }
                } else {
                    $author->setImg($img);
                }
            }
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('authors_index');
        }

        return $this->render('admin/authors/edit.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="authors_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgAuthors $mgAuthor, DeleteItems $deleteItems): Response
    {
        $img = $mgAuthor->getImg();
        $id = $mgAuthor->getId();
        if ($this->isCsrfTokenValid('delete'.$mgAuthor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mgAuthor);
            $entityManager->flush();
            if(!is_null($img)) {
                $deleteItems->deleteDirectoryAndHisFiles($this->getParameter('upload_directory') . '/authors/' . $id . '/');
                /*$path = $this->getParameter('upload_directory') . '/authors/' . $id . '/';
                if(is_dir($path)) {
                    $objects = scandir($path);
                    foreach($objects as $object) {
                        if($object != "." && $object != "..") {
                            unlink($path . $object);
                        }
                    }
                    rmdir($path);
                }*/
            }
        }
        $this->addFlash(
            'danger',
            "Suppression réussie."
        );

        return $this->redirectToRoute('authors_index');
    }

    /**
     * Suprime un dossier avec touts les fichiers inclus dedans
     */
    public function deleteDirectoryAndHisFiles($path)
    {
        if(is_dir($path)) {
            $objects = scandir($path);
            foreach($objects as $object) {
                if($object != "." && $object != "..") {
                    unlink($path . $object);
                }
            }
            rmdir($path);
        }
    }
}
