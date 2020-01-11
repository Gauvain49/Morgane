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
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $categoriesRepository->findBy(['parent' => $parent, 'type' => $type], ['position' => 'ASC']);
        //$treeCategories = $categoriesRepository->findBy(['type' => $type], ['tree_structure' => 'ASC']);
        $children = [];//Tableau pour compter les enfants
        $array = [];//Tableau pour créer l'arborescence
        $filAriane = [];//Tableau du fil d'ariane

        //Comptage du nombre d'enfant pour une catégorie
        foreach ($categories as $value) {
            $catChildren = $categoriesRepository->findBy(['parent' => $value->getId()]);
            $children[$value->getId()] = count($catChildren);
        }

        //Création de l'arborescence parents/enfants
        /*foreach ($treeCategories as $category) {
            $enfant = $categoriesRepository->findOneById($category->getParent());
            $principal = $categoriesRepository->findOneById($category->getId());
            $lang = $repoLang->findOneByCat(array('cat' => $principal));
            $array[] = [
                'parent' => $enfant->getId(),
                'id' => $category->getId(),
                'nom' => $lang->getName()
            ];
        }
        $arborescence = $tree->treeStructure(1, 0, $array, '—');*/

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

        if ($type == 'product') {
            $NavContentOpen = 'NavCatalogOpen';
        } else {
            $NavContentOpen = 'NavContentPostOpen';
        }

/*foreach ($mg_categories as $key => $value) {
$c = new MgCategories();
    $b = $categoriesRepository->find($value['parent_id']);
    $c->setParent($b);
    $c->setPosition($value['position']);
    $c->setActive($value['active']);
    $c->setForceDisplay($value['force_display']);
    $c->setType($value['type']);
    $d = new \Datetime($value['date_add']);
    $c->setDateAdd($d);
    $da = !is_null($value['date_up']) ? new \Datetime($value['date_up']) : null;
    $c->setDateUp($da);
    $c->setLevel($value['level']);
    $entityManager->persist($c);
}
    $entityManager->flush();*/


        $treeStructure = $categoriesRepository->fetchByTreeStructure();

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
            'type' => $type,
            'children' => $children,
            //'arborescence' => $arborescence,
            'filAriane' => $filAriane,
            'parent' => $parent,
            'treeStructure' => $treeStructure,
            $NavContentOpen => true
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
        //Variable pour savoir s'il faudra mettre à jour plusieur position après cet enregistrement.
        $updatePositionCascad = false;

        $form = $this->createForm(CategoriesType::class, $category, ['checkbox' => $checkbox]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$task = $form->getData();
            //dd($task->getParent()->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $level = $repoCat->setLevel($category->getParent());
            $position = $repoCat->setPosition($form->getData()->getParent(), $type);
            if ($form->getData()->getParent()->getId() != 1) {
                //Il faudra éventuellement mettre à jour la position de plusieurs catégories
                $updatePositionCascad = true;
                $fetchCategories = $repoCat->setPositionCascad($position, null, $type);
                foreach ($fetchCategories as $updatePosition) {
                    $newPosition = $updatePosition->getPosition() + 1;
                    $updatePosition->setPosition($newPosition);
                    $updatePosition->setTreeStructure($newPosition);
                    $entityManager->persist($updatePosition);
                }
            }
            foreach ($category->getContents() as $content) {
                $content->setCat($category);
                $entityManager->persist($content);
            }
            $category->setPosition($position);
            $category->setType($type);
            $category->setLevel($level);
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                'success', 'Création réussie !'
            );

            return $this->redirectToRoute('categories_index', ['type' => $type]);
        }

        if ($type == 'product') {
            $NavContentOpen = 'NavCatalogOpen';
        } else {
            $NavContentOpen = 'NavContentPostOpen';
        }

        return $this->render('admin/categories/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'type' => $type,
            $NavContentOpen => true
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categories_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MgCategories $category, MgCategoriesRepository $repoCat, MgCategoryLangRepository $repoLang, TreeStructure $tree): Response
    {
        //Création de l'arborescence pour améliorer
        //la présentation de choix de catégorie parente
        $type = $category->getType();
        $categories = $repoCat->findByType($category->getType());
        $positionOrigine = $category->getPosition();
        $parentOrigine = $category->getParent();
        $parent = $category->getParent()->getId();
        $array = [];

        //$machin = $repoCat->fetchChildren($category->getId(), $category->getType());
        //$chose = $repoCat->getChildren();
        //dump($chose);
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
        //Variable pour savoir s'il faudra mettre à jour plusieur position après cet enregistrement.
        $updatePositionCascad = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            //Si le parent a changé, il faudra redéfinir des positions
            if ($form->getData()->getParent()->getId() != $parent) {
                $updatePositionCascad = true;
                // mise à jour du niveau
                $level = $repoCat->setLevel($category->getParent());
                $category->setLevel($level);
            }
            foreach ($category->getContents() as $content) {
                $content->setCat($category);
                $entityManager->persist($content);
            }
            //On remonte d'un niveau toutes les catégories enfants en leur attribuant l'ex parent de la catégorie actuel
            $catChildrenSameLevel = $repoCat->findBy(['parent' => $category->getId()]);
            foreach ($catChildrenSameLevel as $catChildSameLevel) {
                $catChildSameLevel->setParent($parentOrigine);
                $newLevel = $repoCat->setLevel($parentOrigine);
                $catChildSameLevel->setLevel($newLevel);
                $entityManager->persist($catChildSameLevel);
            }
            if ($updatePositionCascad) {
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
                $position = 1;
                foreach ($arborescence as $value) {
                    $updateCatCascad = $repoCat->find($value);
                    $updateCatCascad->setPosition($position);
                    $entityManager->persist($updateCatCascad);
                    $position++;
                }
            }
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                'success', 'Modification réussie !'
            );

            return $this->redirectToRoute('categories_index', ['type' => $type]);
        }

        if ($type == 'product') {
            $NavContentOpen = 'NavCatalogOpen';
        } else {
            $NavContentOpen = 'NavContentPostOpen';
        }

        return $this->render('admin/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'type' => $category->getType(),
            $NavContentOpen => true
        ]);
    }

    /**
     * @Route("/{id}/update-position/{update}", name="categories_position_edit", methods={"GET","POST"})
     */
    public function updatePosition(Request $request, $update, MgCategories $category, MgCategoriesRepository $repoCat)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $parent = $category->getParent()->getId();
        //On récupère la position actuel de la catégorie
        if ($update == 'down' || $update == 'up') {
            $updatePosition = $repoCat->switchPosition($category, $update);
            foreach ($updatePosition as $id => $newPosition) {
                $upCategory = $repoCat->find($id);
                $upCategory->setPosition($newPosition);
                $entityManager->persist($upCategory);
            }
            $entityManager->flush();

        }

        return $this->redirectToRoute('categories_index', ['type' => $category->getType(), 'parent' => $parent]);
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
                $newLevel = $repo->setLevel($parentOrigine);
                $child->setLevel($newLevel);
                $entityManager->persist($child);
            }
            //On remonte d'un cran toutes les positions supérieures
            $upperPosition = $repo->getPositionUpper($category->getPosition(), $category->getType());
            foreach ($upperPosition as $oneCategory) {
                if ($oneCategory->getId() != $category->getId()) {
                    $newPosition = $oneCategory->getPosition() - 1;
                    $oneCategory->setPosition($newPosition);
                    $entityManager->persist($oneCategory);
                }
            }            
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categories_index', ['type' => $category->getType()]);
    }
}
