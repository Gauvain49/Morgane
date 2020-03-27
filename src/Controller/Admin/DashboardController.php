<?php

namespace App\Controller\Admin;

use App\Repository\MgCustomersRepository;
use App\Repository\MgOrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin/", name="dashboard", methods={"GET"})
     */
    public function index(MgCustomersRepository $repoCustomer, MgOrdersRepository $repoOrder): Response
    {
    	$month = date("n");
    	if ($month == 1) {
    		$prevMonth = 12;
    	} else {
    		$prevMonth = $month-1;
    	}
    	$ordersAndSalesOfMonth = $repoOrder->salesOfMounth($month);
    	$ordersAndSalesOfPrevMonth = $repoOrder->salesOfMounth($prevMonth);
    	$salesOfMounth = 0;
    	foreach ($ordersAndSalesOfMonth as $value) {
    		$salesOfMounth += $value['total_price_all_taxes'];
    	}
    	$salesOfPrevMounth = 0;
    	foreach ($ordersAndSalesOfPrevMonth as $value) {
    		$salesOfPrevMounth += $value['total_price_all_taxes'];
    	}

    	$year = date("Y");
    	$prevYear = $year-1;
    	$salesOfYear = $repoOrder->salesOfYear($year);
    	$salesOfPrevYear = $repoOrder->salesOfYear($prevYear);
    	$saleOfYearByMonth = $saleOfPrevYearByMonth = $orderOfYearByMonth = $orderOfPrevYearByMonth = ['01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0];
    	foreach ($salesOfYear as $value) {
    		if (array_key_exists($value['date_add']->format('m'), $saleOfYearByMonth)) {
    			$saleOfYearByMonth[$value['date_add']->format('m')] += $value['total_price_all_taxes'];
    			$orderOfYearByMonth[$value['date_add']->format('m')] += 1;
    		}
    	}
    	foreach ($salesOfPrevYear as $value) {
    		if (array_key_exists($value['date_add']->format('m'), $saleOfPrevYearByMonth)) {
    			$saleOfPrevYearByMonth[$value['date_add']->format('m')] += $value['total_price_all_taxes'];
    			$orderOfPrevYearByMonth[$value['date_add']->format('m')] += 1;
    		}
    	}

        return $this->render('admin/dashboard/index.html.twig', [
            'customers' => count($repoCustomer->findAll()),
            'salesOfMonth' => $salesOfMounth,
            'salesOfPrevMounth' => $salesOfPrevMounth,
            'ordersOfMonth' => count($ordersAndSalesOfMonth),
            'ordersOfPrevMonth' => count($ordersAndSalesOfPrevMonth),
            'saleOfYearByMonth' => $saleOfYearByMonth,
            'saleOfPrevYearByMonth' => $saleOfPrevYearByMonth,
            'orderOfYearByMonth' => $orderOfYearByMonth,
            'orderOfPrevYearByMonth' => $orderOfPrevYearByMonth
        ]);
    }
}
