<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\MgParametersAddressesRepository;
use App\Repository\MgParametersRepository;
use App\Repository\MgPostsRepository;
use App\Repository\MgProductsImagesRepository;
use App\Repository\MgProductsRepository;
use App\Repository\MgSuppliersRepository;
use App\Services\Languages;
use App\Services\Pagination;
use App\Services\SendEmails;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    
    /**
     * @Route("/result-search/{page<\d+>?1}", name="resultSearch", methods={"GET","POST"})
     */
    public function resultSearch($page, Request $request, MgProductsRepository $repoProducts, MgProductsImagesRepository $productsImagesRepository, Pagination $pagination)
    {
        $pagination->setEntityClass(MgProducts::class)
                    ->setPage($page)
                    ->setLimit(1000);
        $search = ($request->query->get('search_product'))['search'];
        $products = $repoProducts->searchProduct($search);
        //Construction de la pagination
        $countProduct = count($products);
        //dd($countProduct);
        $pages = $pagination->getPages($countProduct);
        $products = $repoProducts->searchProduct($search, $pagination->getLimit(), $pagination->getOffset());
        //Récupération des images
        $imgPath = [];
        foreach ($products as $product) {
            $image = $productsImagesRepository->findOneBy(['product' => $product->getId(), 'cover' => true]);
            if(!empty($image)) {
                $getImage = $productsImagesRepository->getImgBySize($this->getParameter('upload_directory_products') . '/', $image->getId(), $productsImagesRepository::MEDIUM, 'square');
                if (!is_null($getImage)) {
                    $imgPath[$product->getId()] = $getImage;
                }
            }
        }
        
        return $this->render('main/search/result.html.twig', [
            'products' => $products,
            'imgPath' => $imgPath,
            'page' => $page,
            'pages' => $pages,
            'search' => $search,
            'nbResult' => $countProduct
        ]);
    }

    /**
     * Gestion de la page Contact
     *
     * @Route("/contact", name="contact")
     *
     * @return Response
     */
    public function contact(Request $request, SendEmails $sendEmails, MgParametersRepository $repoParams, MgParametersAddressesRepository $repoAddresses)
    {
        $contact = new Contact();
        $param = $repoParams->find(1);
        $addresses = $repoAddresses->findAll();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $msg = '<p><strong>Bonjour,</strong></p>'."\n\n";
            $msg .= '<p><em>Un message vient de vous être envoyé depuis le site \'' . $param->getTitle() . '\' </em></p>'."\r\n\r\n";
            $msg .= "<p><strong>Civilité :</strong>\t{$task->getCivility()}<br/>\r\n";
            $msg .= "<p><strong>Nom :</strong>\t{$task->getName()}<br/>\r\n";
            $msg .= "<strong>Prénom :</strong>\t{$task->getFirstname()}<br/>\r\n";
            $msg .= "<strong>Email :</strong>\t{$task->getEmail()}<br/>\r\n";
            $msg .= "<strong>Message :</strong>\t{$task->getMessage()}</p>\r\n";
            //dd($task->getName());
            $sendContact = $sendEmails->sendMailContact($msg, $msg,
        $task->getEmail(), $param->getTitle(), $param->getEmailContact(), $task->getFirstname() . ' ' . $task->getName(), $task->getSubject());
            $this->addFlash(
                'success',
                "votre message a bien été envoyé ! Il sera traité dans les plus bref délais."
                );
            return $this->redirectToRoute('contact');
        }
        return $this->render('main/contact.html.twig', [
            'form' => $form->createView(),
            'addresses' => $addresses
        ]);
    }

    /**
     * @Route("/{slug}", name="page")
     */
    public function page($slug, MgPostsRepository $repoPosts, MgSuppliersRepository $repoSuppliers, Languages $languages)
    {
    	$page = $repoPosts->findOnePostBySlug('page', $languages->languageDefault(), $slug);
    	if ($slug == 'partenaires') {
    		$suppliers = $repoSuppliers->findAll();
    	} else {
    		$suppliers = [];
    	}
        return $this->render('main/page.html.twig', [
        	'page' => $page,
            'suppliers' => $suppliers,
        ]);
    }
}
