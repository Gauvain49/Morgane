<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DiscountPriceExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('discountPriceAT', [$this, 'calculDiscountPriceAT']),
        ];
    }

    /**
     * Calcul d'un prix TTC remisé
     * @param $type est le type de remise
     * @param $onTaxe définie si la remise se calcul sur le HT ou TTC
     * @param $discount est le montant ou taux de remise
     * @param $taxe est la taxe du produit
     * @param $net est le prix HT du produit
     * @param $allTaxes est le prix TTC du produit
     */
    public function calculDiscountPriceAT($discount, $type, $onTaxe, $taxe, $net, $allTaxes)
    {
        $price = 0;
        //Si la réduction est un montant
        if ($type == 'amount') {
            //Si c'est sur le HT
            if ($onTaxe == 0) {
                //On calcul d'abord le net
                //Puis on calcul le ttc
                $price = round($net - $discount, 2);
                $price = round($price + ($price * $taxe / 100), 2);
            //Si c'est sur le ttc
            } else {
                $price = round($allTaxes - $discount);
            }
        //Si la réduction est un pourcentage
        } else {
            if ($onTaxe == 0) {
                $price = round($net - ($net * $discount / 100), 2);
                $price = round($price + ($price * $taxe / 100), 2);
            } else {
                $price = round($allTaxes - ($allTaxes * $discount / 100), 2);
            }
        }
        return $price;
    }
}