<?php

namespace App\Controller\Admin;

use App\Entity\MgCategories;
use App\Entity\MgCategoryLang;
use App\Form\CategoriesType;
use App\Repository\MgCategoriesRepository;
use App\Repository\MgCategoryLangRepository;
use App\Services\TreeStructure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categories")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/{type}/{parent<\d+>?1}", name="categories_index", methods={"GET"})
     */
    public function index($type, $parent, MgCategoriesRepository $categoriesRepository, MgCategoryLangRepository $repoLang, TreeStructure $tree): Response
    {
        $categories = $categoriesRepository->findBy(['parent' => $parent, 'type' => $type], ['position' => 'ASC']);
        $treeCategories = $categoriesRepository->findBy(['type' => $type], ['position' => 'ASC']);
        $children = [];//Tableau pour compter les enfants
        $array = [];//Tableau pour créer l'arborescence
        $filAriane = [];//Tableau du fil d'ariane

        //Comptage du nombre d'enfant pour une catégorie
        foreach ($categories as $value) {
            $catChildren = $categoriesRepository->findBy(['parent' => $value->getId()]);
            $children[$value->getId()] = count($catChildren);
        }

        //Création de l'arborescence parents/enfants
        foreach ($treeCategories as $category) {
            $enfant = $categoriesRepository->findOneById($category->getParent());
            $principal = $categoriesRepository->findOneById($category->getId());
            $lang = $repoLang->findOneByCat(array('cat' => $principal));
            $array[] = [
                'parent' => $enfant->getId(),
                'id' => $category->getId(),
                'nom' => $lang->getName()
            ];
        }
        $arborescence = $tree->treeStructure(1, 0, $array, '—');

        //Création du fil d'ariane
        $parentID = $parent;
        while ($parentID != 1) {
            $getParent = $categoriesRepository->findOneBy(['id' => $parentID]);
            foreach ($getParent->getContents() as $v) {
                if ($v->getLang()->getId() == 1) {
                    $filAriane[$parentID] = $v->getName();
                }
            }
            $parentID = $getParent->getParent()->getId();
        }
        $filAriane = array_reverse($filAriane, true);

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
            'type' => $type,
            'children' => $children,
            'arborescence' => $arborescence,
            'filAriane' => $filAriane,
            'parent' => $parent
        ]);
    }

    /**
     * @Route("/new/{type}", name="categories_new", methods={"GET","POST"})
     */
    public function new($type, Request $request, MgCategoriesRepository $repoCat, MgCategoryLangRepository $repoLang, TreeStructure $tree): Response
    {
        $category = new MgCategories();
        //$content = new MgCategoryLang();

        //Création de l'arborescence pour améliorer
        //la présentation de choix de catégorie parente
        $categories = $repoCat->findByType($type);
        $array = [];
        foreach ($categories as $cat) {
            $enfant = $repoCat->findOneById($cat->getParent());
            $principal = $repoCat->findOneById($cat->getId());
            $lang = $repoLang->findOneByCat(array('cat' => $principal));
            $array[] = [
                'parent' => $enfant->getId(),
                'id' => $cat->getId(),
                'nom' => $lang->getName()
            ];
        }
        $arborescence = $tree->treeStructure(1, 0, $array, '—');
        $checkbox = [];
        $checkbox['Aucun'] = $repoCat->find(1);
        foreach ($arborescence as $key => $value) {
            $checkbox[$key] = $repoCat->find($value);
        }

        $form = $this->createForm(CategoriesType::class, $category, ['checkbox' => $checkbox]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$task = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($category->getContents() as $content) {
                $content->setCat($category);
                $entityManager->persist($content);
            }
            if (is_null($form->getData()->getPosition())) {
                $category->setPosition($repoCat->setPosition($category->getParent()));
            }
            $category->setType($type);
            $category->setLevel($repoCat->setLevel($category->getParent()));
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('categories_index', ['type' => $type]);
        }

        return $this->render('admin/categories/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'type' => $type
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categories_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgCategories $category, MgCategoriesRepository $repoCat, MgCategoryLangRepository $repoLang, TreeStructure $tree): Response
    {
        //Création de l'arborescence pour améliorer
        //la présentation de choix de catégorie parente
        $categories = $repoCat->findByType($category->getType());
        $array = [];
        foreach ($categories as $cat) {
            $enfant = $repoCat->findOneById($cat->getParent());
            $principal = $repoCat->findOneById($cat->getId());
            $lang = $repoLang->findOneByCat(array('cat' => $principal));
            $array[] = [
                'parent' => $enfant->getId(),
                'id' => $cat->getId(),
                'nom' => $lang->getName()
            ];
        }
        $arborescence = $tree->treeStructure(1, 0, $array, '—');
        $checkbox = [];
        $checkbox['Aucun'] = $repoCat->find(1);
        foreach ($arborescence as $key => $value) {
            $checkbox[$key] = $repoCat->find($value);
        }
        $form = $this->createForm(CategoriesType::class, $category, ['checkbox' => $checkbox]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categories_edit', ['id' => $category->getId()]);
        }

        return $this->render('admin/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'type' => $category->getType()
        ]);
    }

    /**
     * @Route("/{id}", name="categories_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MgCategories $category, MgCategoriesRepository $repo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $type = $category->getType();
            //On remonte d'un cran les enfants de la catégorie
            $children = $repo->findByParent($category);
            foreach ($children as $child) {
                $child->setParent($category->getParent());
                $entityManager->persist($child);
                $entityManager->flush();
            }
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categories_index', ['type' => $category->getType()]);
    }
}
