<?php

namespace App\Controller\Admin;

use App\Form\ParametersType;
use App\Repository\MgParametersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParametersController extends AbstractController
{
    /**
     * @Route("/admin/parameters", name="parameters")
     */
    public function index(Request $request, MgParametersRepository $repoParams)
    {
        $parameter = $repoParams->find(1);
        $form = $this->createForm(ParametersType::class, $parameter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parameters');
        }
        return $this->render('admin/parameters/index.html.twig', [
            'mg_parameter' => $parameter,
            'form' => $form->createView(),
        ]);
    }
}
