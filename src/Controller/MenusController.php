<?php

namespace App\Controller;

use App\Entity\MgPosts;
use App\Repository\MgCategoriesRepository;
use App\Services\Languages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenusController extends AbstractController
{

    /**
     * Créer le menu de navigation du front avec les pages
     */
    public function displayMenu()
    {
        $pages = $this->getDoctrine()->getRepository(MgPosts::class)->findPostsWithDateManage('page', 'publish');
        $pages_by_id = [];
        foreach ($pages as $page) {
            $pages_by_id[$page->getId()] = $page;
        }
        foreach ($pages as $key => $item) {
            if (!is_null($item->getParent())) {
                $pages_by_id[$item->getParent()->getId()]->children[] = $item;
                unset($pages[$key]);
            }
        }

        return $this->render('main/partials/_navMain.html.twig', [
            'pages' => $pages
        ]);

        //return $pages;
    }

    /**
     * Affiche le menu sur les écrans classique
     */
    public function displayMenuScreen()
    {
        $pages = $this->displayMenu();

         return $this->render('main/partials/_navMain.html.twig', [
            'pages' => $pages
        ]);
    }

    /**
     * Affiche le menu sur les écrans Mobile
     */
    public function displayMenuMobile()
    {
        $pages = $this->displayMenu();

         return $this->render('main/partials/_navMobileMain.html.twig', [
            'pages' => $pages
        ]);
    }

    /**
     * Créer le menu de navigation du front avec les principales catégories
     */
    public function displayMenuCat(MgCategoriesRepository $categoriesRepository, Languages $languages)
    {
        $categories = $categoriesRepository->buildMenuCat($languages->languageDefault());
        //On exclu la catégorie racine
        //unset($categories[0]);
        $categories_by_id = [];
        foreach ($categories as $categorie) {
            $categories_by_id[$categorie->getId()] = $categorie;
        }
        foreach ($categories as $key => $item) {
            if ($item->getParent()->getId() > 1) {
                $categories_by_id[$item->getParent()->getId()]->catChildren[] = $item;
                unset($categories[$key]);
            }
        }

        //dd($categories);
        return $this->render('main/partials/_navMainCat.html.twig', [
            'categories' => $categories
        ]);
    }
}