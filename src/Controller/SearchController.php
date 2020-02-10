<?php

namespace App\Controller;

use App\Form\SearchProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/form_search", name="form_search")
     */
    public function form(Request $request)
    {
        return $this->render('main/search/_form.html.twig');
    }

    /**
     * @Route("/search", name="search", methods={"GET","POST"})
     */
    public function search(Request $request)
    {
        $form = $this->createForm(SearchProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$task = $form->getData();
        	dd($task);
        }
        return $this->render('main/search/_form.html.twig', [
        	'form' => $form->createView()
        ]);
    }
}
