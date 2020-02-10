<?php

namespace App\Controller\Admin;

use App\Entity\MgPaymentCheck;
use App\Entity\MgPaymentsModes;
use App\Form\PaymentCheckType;
use App\Repository\MgPaymentCheckRepository;
use App\Repository\MgPaymentsModesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PaymentsModeController extends AbstractController
{
    /**
     * @Route("admin/payments", name="payments_mode")
     */
    public function index(MgPaymentsModesRepository $paymentsMode)
    {
    	$payments = $paymentsMode->findAll();
        return $this->render('admin/payments/index.html.twig', [
            'payments' => $payments,
        ]);
    }

    /**
     * @Route("admin/payments/payment-display/{id}", name="payment_display", methods="GET")
     */
    public function display(Request $request, MgPaymentsModes $payment)
    {
        if ($payment->getActif() == 0) {
            $payment->setActif(true);
            $message = 'Ce mode de paiement est maintenant proposé.';
        } else {
            $payment->setActif(false);
            $message = 'Ce mode de paiement n\'est plus porposé.';
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($payment);
        $entityManager->flush();

        return $this->json(['code' => 200, 'message' => $message], 200);
    }

    /**
     * Information sur les paiements par chèque
     *
     * @Route("admin/payments/check", name="setting_check")
     */
    public function editCheck(MgPaymentCheckRepository $repo, MgPaymentsModesRepository $paymentsModes, Request $request)
    {
        $check = $repo->find(1);
        if (empty($check)) {
            $check = new MgPaymentCheck();
        }
        $form = $this->createForm(PaymentCheckType::class, $check);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$mode = $paymentsModes->find(2);
        	$check->setMode($mode);
            $em = $this->getDoctrine()->getManager();
            $em->persist($check);
            $em->flush();
            //$this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                "Modification réussie."
            );

            return $this->redirectToRoute('setting_check');
        }
        return $this->render('admin/payments/check/check.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
