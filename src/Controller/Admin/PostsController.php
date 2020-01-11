<?php

namespace App\Controller\Admin;

use App\Entity\MgPosts;
use App\Form\PostsType;
use App\Repository\MgCategoriesRepository;
use App\Repository\MgPostsLangRepository;
use App\Repository\MgPostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/posts", name="posts_")
 */
class PostsController extends AbstractController
{
    /**
     * Permet de gérer l'ordre d'apparition des boutons d'un menu
     *
     * @Route("/position-post/{parent}", name="position")
     */
    public function postPosition(MgPostsRepository $postRepo, MgPostsLangRepository $postLang, $parent = null)
    {
        $posts = $postRepo->findBy(['type' => 'page', 'parent' => $parent], ['position' => 'ASC']);
        $childrens = [];
        foreach ($posts as $post) {
            $check_childrens = $postRepo->findByParent($post);
            $childrens[$post->getId()] = count($check_childrens);
        }
        $breadcrumb = [];
        $parent_breadcrumb = $parent;
        $post = $postRepo->findOneById($parent_breadcrumb);
        while (!is_null($parent_breadcrumb)) {
            $post_parent = $postRepo->findOneById($parent_breadcrumb)->getId();
            //dump($post_parent);
            $breadcrumb[$post_parent] = $postLang->findOneBy(['post' => $post, 'lang' => 1])->getTitle();
            $parent_breadcrumb = $post->getParent();
            $post = $postRepo->findOneById($parent_breadcrumb);
        }
        $breadcrumb = array_reverse($breadcrumb, true);
        return $this->render('admin/posts/post-position.html.twig', [
                'posts' => $posts,
                'childrens' => $childrens,
                'breadcrumb' => $breadcrumb,
                'parent' => $parent
            ]
        );
    }

    /**
     * Permet de mettre à jour l'ordre d'apparition des boutons d'un menu
     *
     * @Route("/update-position-post", name="update_position", methods="POST")
     */
    public function updatePostPosition(MgPostsRepository $mgPosts, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $status = "error";
        $message = "";
        foreach ($request as $key => $value) {
            if ($key == 'request') {
                foreach ($value as $k => $v) {
                    $truc = $v;
                    break;
                }
                break;
            }
        }
        foreach ($truc as $key => $value) {
            $post = $mgPosts->find($key);
            if ($post->getPosition() != $value) {
                $post->setPosition($value);
                $em->persist($post);
                $em->flush();
            }
            $status = "success";
            $message = "Positionnement mis à jour!";
        }
        $response = array(
            'status' => $status,
            'message' => $message
        );

        return new JsonResponse($response);
    }

    /**
     * @Route("/{role}", name="index", methods={"GET"})
     * @Route("/posts/{role}/{status}", name="index_status", methods={"GET"})
     */
    public function index(MgPostsRepository $postsRepository, $role, $status = false): Response
    {
        if (!$status) {
            $posts = $postsRepository->findByType($role, ['id' => 'DESC']);
        } else {
            $posts = $postsRepository->findBy(['type' => $role, 'status' => $status], ['id' => 'DESC']);
        }
        $published = $postsRepository->findBy(['type' => $role, 'status' => 'publish']);
        $draft = $postsRepository->findBy(['type' => $role, 'status' => 'draft']);
        $trash = $postsRepository->findBy(['type' => $role, 'status' => 'trash']);

        if ($role == 'page') {
            $NavContentOpen = 'NavContentPageOpen';
        } else {
            $NavContentOpen = 'NavContentPostOpen';
        }
        
        return $this->render('admin/posts/index.html.twig', [
            'posts' => $posts,
            'role' => $role,
            'published' => $published,
            'draft' => $draft,
            'trash' => $trash,
            $NavContentOpen => true
        ]);
    }

    /**
     * @Route("/new/{role}", name="new", methods={"GET","POST"})
     */
    public function new($role, Request $request, MgPostsRepository $repoPosts, MgCategoriesRepository $repoCat): Response
    {
        $post = new MgPosts();
        //$content = new MgPostsLang();
        if ($role == $post::PAGE) {
            $listCascad = $repoPosts->findAllByArborescence();
        } elseif ($role == $post::POST) {
            $listCascad = $repoCat->findAllByArborescence('post');
        }
        $form = $this->createForm(PostsType::class, $post, ['checkbox' => $listCascad, 'action' => $role]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($post->getContents() as $content) {
                $content->setPost($post);
                $content->setReviser($this->getUser());
                $entityManager->persist($content);
            }
            //Si c'est une page, on donne la position par rapport à la dernière donnée
            if ($role == $post::PAGE) {
                $post->setPosition($repoPosts->setPosition($post->getParent()));
            }
            if ($role == $post::POST) {
                //Si aucune catégorie n'est sélectionné, on attribue 'Non classé' (racine) par défaut
                if(count($post->getCategories()) == 0) {
                    $post->addCategory($repoCat->find(1));
                } else {
                    //Sinon, on attribue les catégories sélectionnée
                    foreach ($post->getCategories() as $category) {
                        //$cat = $repoCat->findOneById($category);
                        $cat = $repoCat->find($category);
                        $post->addCategory($cat);
                    }
                }
            }
            // Auteur de la page
            $post->setUser($this->getUser());
            // Status de la page
            if ($form->get('post_draft')->isClicked()) {
                $post->setStatus('draft');
            } elseif ($form->get('post_view')->isClicked()) {
                $post->setStatus('draft');
            } elseif ($form->get('post_publish')->isClicked()) {
                $post->setStatus('publish');
            }
            // Type de la page
            $post->setType($role);
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "Ajout réussi !"
            );

            return $this->redirectToRoute('posts_edit', ['id' => $post->getId()]);
        }

        if ($role == 'page') {
            $NavContentOpen = 'NavContentPageOpen';
        } else {
            $NavContentOpen = 'NavContentPostOpen';
        }

        return $this->render('admin/posts/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'role' => $role,
            $NavContentOpen => true
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgPosts $post, MgPostsRepository $repoPosts, MgCategoriesRepository $repoCat): Response
    {
        $role = $post->getType();
        //Création des variables servant à l'historique des contenues
        $recTitle = [];//Récupération du titre
        $recContent = [];//Récupération du contenu
        $contentPost = [];//Récupération de l'historique éventuellement présent
        $i = 0;
        foreach ($post->getContents() as $key => $value) {
            //On stock le titre du post et son contenu dans leur langue associé
            $recTitle[$value->getLang()->getId()] = $value->getTitle();
            $recContent[$value->getLang()->getId()] = $value->getContent();
            //On déploie l'historique pour regénérer les clés...
            foreach ($value->getRevision() as $array) {
                foreach ($array as $k => $v) {
                    $contentPost[$value->getLang()->getId()][$i][$k] = $v;
                }
                $i++;
            }
            //... puis on lui ajoute le titre et contenu stockés.
            $contentPost[$value->getLang()->getId()][$i]['title'] = $value->getTitle();
            $contentPost[$value->getLang()->getId()][$i]['content'] = $value->getContent();
            $contentPost[$value->getLang()->getId()][$i]['reviser'] = $this->getUser()->getId();
            $contentPost[$value->getLang()->getId()][$i]['date_up'] = new \Datetime();
            // Bien que créé tout de suite dans sa version définitive, le tableau
            // $contentPost ne sera utilisé que si le titre ou le contenu ont changé.
            // Si ce n'est pas le cas, générer un historique est inutile.
        }
        if ($role == $post::PAGE) {
            $listCascad = $repoPosts->findAllByArborescence();
        } elseif ($role == $post::POST) {
            $listCascad = $repoCat->findAllByArborescence('post');
        }
        //Récupération des catégories déjà sélectionné (en attendant de trouver mieux)
        $cat_selected = [];
        $recup_cat = $post->getCategories();
        foreach ($recup_cat as $value) {
            $cat_selected[] = $value->getId();
        }
        $form = $this->createForm(PostsType::class, $post, ['checkbox' => $listCascad]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($post->getContents() as $content) {
                $content->setPost($post);
                $content->setReviser($this->getUser());
                $content->setDateUp(new \Datetime());

                //On vérifie s'il faut générer un historique
                if ($recTitle[$content->getLang()->getId()] != $content->getTitle() || $recContent[$content->getLang()->getId()] != $content->getContent()) {
                    $content->setRevision($contentPost[$content->getLang()->getId()]);
                }
                $entityManager->persist($content);
            }
            if ($form->get('post_draft')->isClicked()) {
                $post->setStatus('draft');
            } elseif ($form->get('post_view')->isClicked()) {
                $post->setStatus('draft');
            } elseif ($form->get('post_publish')->isClicked()) {
                $post->setStatus('publish');
            }
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "Modification réussie !"
            );

            return $this->redirectToRoute('posts_edit', ['id' => $post->getId()]);
        }

        if ($role == 'page') {
            $NavContentOpen = 'NavContentPageOpen';
        } else {
            $NavContentOpen = 'NavContentPostOpen';
        }

        return $this->render('admin/posts/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'role' => $role,
            'catSelected' => $cat_selected,
            $NavContentOpen => true
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgPosts $post, MgPostsRepository $repo): Response
    {
        $role = $post->getType();
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            //Il faut d'abord vérifier si la page est parente d'autres pages
            if ($role == 'page') {
                $postChildren = $repo->findBy(['parent' => $post]);
                if (count($postChildren) > 0) {
                    if (is_null($post->getParent())) {
                        $parent = null;
                    } else {
                        $parent = $repo->find($post->getParent());
                    }
                    foreach ($postChildren as $child) {
                        $child->setParent($parent);
                        $em->persist($child);
                        $em->flush();
                    }
                }                
            }

            $entityManager->remove($post);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "La suppression est réussie."
            );
        }

        return $this->redirectToRoute('posts_index', ['role' => $role]);
    }
}
