<?php
namespace App\Services;

use App\Repository\MgCarriersRepository;
use App\Repository\MgDepartmentsFrenchRepository;
use App\Repository\MgProductsRepository;
use App\Repository\MgTaxesRepository;
use App\Services\CartService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShippingService
{
    protected $session;
    protected $cart;
    protected $taxes;
    protected $carrier;
    protected $department;
    protected $shipping;

	public function __construct(SessionInterface $session, CartService $cart, MgCarriersRepository $carrier, MgTaxesRepository $taxes, MgDepartmentsFrenchRepository $department) {
        $this->session = $session;
        $this->carrier = $carrier;
        $this->taxes = $taxes;
        $this->department = $department;
        $this->cart = $cart;
	}

    /**
     * A partir du panier, on créée un tableau associatif
     * regroupant la somme des quantités, prix et poids par id de transporteur
     */
    public function getValuesTypeByCarrier()
    {
        $values = [];
        foreach ($this->cart->cart() as $item) {
            if ($item['product']->getType() != 'downloadable' || $item['product']->getType() != 'downloadable_exclu') {
                if (array_key_exists($item['product']->getCarrier()->getId(), $values)) {
                    $values[$item['product']->getCarrier()->getId()]['qty'] += $item['qty'];
                    $values[$item['product']->getCarrier()->getId()]['price'] += $item['amount']['priceNetAllTaxes']*$item['qty'];
                    $values[$item['product']->getCarrier()->getId()]['weight'] += $item['product']->getWeight()*$item['qty'];
                    $values[$item['product']->getCarrier()->getId()]['additionnal'] += $item['product']->getAdditionnalShippingCost()*$item['qty'];
                } else {
                    $values[$item['product']->getCarrier()->getId()]['qty'] = $item['qty'];
                    $values[$item['product']->getCarrier()->getId()]['price'] = $item['amount']['priceNetAllTaxes']*$item['qty'];
                    $values[$item['product']->getCarrier()->getId()]['weight'] = $item['product']->getWeight()*$item['qty'];
                    $values[$item['product']->getCarrier()->getId()]['additionnal'] = $item['product']->getAdditionnalShippingCost()*$item['qty'];
                }
            }
        }
        //dd($values);
        return $values;
    }

    public function getShipping($countryCustomer, $zip)
    {
        if (!$this->session->has('shipping')) {
            $this->session->set('shipping', []);
        }

        $shipping = [];
        $getDataCart = $this->getValuesTypeByCarrier();

        foreach ($getDataCart as $id_carrier => $item) {
            //dd($id_carrier);
                $carrier = $this->carrier->find($id_carrier);
                if ($carrier->getConfig()->getBillingOn() == 'qty') {
                    //Si le client a une livraison en France, le prix peut dépendre de son département, sa région ou son pays (France)
                    if ($countryCustomer == 8) {
                        //On vérifie d'abord s'il y a un prix au département
                        if (count($carrier->getConfig()->getStepsDeps()) > 0) {
                            //$shipping[$id_carrier] = $this->priceByDepartment($carrier->getConfig()->getStepsDeps(), $this->cart->totalQuantity(), $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                            $shipping[$id_carrier] = $this->priceByDepartment($carrier->getConfig()->getStepsDeps(), $item['qty'], $item['additionnal'], $zip, $carrier, $carrier->getConfig()->getOutOfRange());

                        } elseif(count($carrier->getConfig()->getStepsRegions()) > 0) {// Sinon s'il y a un prix à la région
                            $shipping[$id_carrier] = $this->priceByRegion($carrier->getConfig()->getStepsRegions(), $item['qty'], $item['additionnal'], $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                        } elseif(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping[$id_carrier] = $this->priceByCountry($carrier->getConfig()->getSteps(), $item['qty'], $item['additionnal'], $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    } else {//Sinon il dépend uniquement du pays
                        if(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping[$id_carrier] = $this->priceByCountry($carrier->getConfig()->getSteps(), $item['qty'], $item['additionnal'], $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    }
                } elseif($carrier->getConfig()->getBillingOn() == 'price') {
                    //Si le client a une livraison en France, le prix peut dépendre de son département, sa région ou son pays (France)
                    if ($countryCustomer == 8) {
                        //On vérifie d'abord s'il y a un prix au département
                        if (count($carrier->getConfig()->getStepsDeps()) > 0) {
                            $shipping[$id_carrier] = $this->priceByDepartment($carrier->getConfig()->getStepsDeps(), $item['price'], $item['additionnal'], $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                        } elseif(count($carrier->getConfig()->getStepsRegions()) > 0) {// Sinon s'il y a un prix à la région
                            $shipping[$id_carrier] = $this->priceByRegion($carrier->getConfig()->getStepsRegions(), $item['price'], $item['additionnal'], $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                        } elseif(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping[$id_carrier] = $this->priceByCountry($carrier->getConfig()->getSteps(), $item['price'], $item['additionnal'], $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    } else {//Sinon il dépend uniquement du pays
                        if(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping[$id_carrier] = $this->priceByCountry($carrier->getConfig()->getSteps(), $item['price'], $item['additionnal'], $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    }
                } elseif($carrier->getConfig()->getBillingOn() == 'weight') {
                    //$weight = $this->getWeightProduct();
                    //Si le client a une livraison en France, le prix peut dépendre de son département, sa région ou son pays (France)
                    if ($countryCustomer == 8) {
                        //On vérifie d'abord s'il y a un prix au département
                        if (count($carrier->getConfig()->getStepsDeps()) > 0) {
                            $shipping[$id_carrier] = $this->priceByDepartment($carrier->getConfig()->getStepsDeps(), $item['weight'], $item['additionnal'], $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                        } elseif(count($carrier->getConfig()->getStepsRegions()) > 0) {// Sinon s'il y a un prix à la région
                            $shipping[$id_carrier] = $this->priceByRegion($carrier->getConfig()->getStepsRegions(), $item['weight'], $item['additionnal'], $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                        } elseif(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping[$id_carrier] = $this->priceByCountry($carrier->getConfig()->getSteps(), $weight[$carrier->getId()], $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    } else {//Sinon il dépend uniquement du pays
                        if(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping[$id_carrier] = $this->priceByCountry($carrier->getConfig()->getSteps(), $item['weight'], $item['additionnal'], $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    }
                }
        }
        $this->setShipping($shipping);
        $this->session->set('shipping', $shipping);
        return $shipping;
    }

    private function setShipping(array $shipping): self
    {
        $this->shipping = $shipping;
        return $this;
    }

    /**
     * Affiche le coût total de livraison
     */
    public function totalCostShipping()
    {
        $totalShipping = ['price' => 0, 'taxes' => 0];
        foreach (current($this->session->get('shipping')) as $value) {
            $totalShipping['price'] += $value['price'];
            $totalShipping['taxes'] += $value['taxes'];
        }
        return $totalShipping;
    }

    public function priceByDepartment($stepsDeps, $comparisonValue, $additionnal, $zip, $carrier, $outOfRange)
    {
        //On déclare la variable recueillant le prix de la livraison
        $shipping = [];
        foreach ($stepsDeps as $value) {
            if ($comparisonValue >= $value->getStepMin() && $comparisonValue < $value->getStepMax()) {
                foreach ($value->getAmountDepartments() as $department) {
                    if ($zip == $this->department->find($department->getStepDepartment())->getCodeInsee()) {
                        $shipping['price'] = $department->getDepartmentAmount() + $additionnal;
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping['taxes'] = round($shipping['price'] * $taxe->getTaxeRate() / 100, 2);
                        return $shipping;
                    }
                }
            }
        }
        //Si aucun prix n'est retourné par le return :
        //Si le hors tranche est gratuit
        if ($outOfRange == 'free') {
            $shipping['price'] = 0;
            $shipping['taxes'] = 0;
            return $shipping;
        } elseif ($outOfRange == 'hit') {//sinon si le hors tranche prend la valeur la plus haute
            foreach ($stepsDeps as $value) {
                $hit = $value;
            }
            if ($comparisonValue >= $hit->getStepMin() && $comparisonValue < $hit->getStepMax()) {
                foreach ($hit->getAmountDepartments() as $department) {
                    if ($zip == $this->department->find($department->getStepDepartment())->getCodeInsee()) {
                        $shipping['price'] = $department->getDepartmentAmount() + $additionnal;
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping['taxes'] = round($shipping['price'] * $taxe->getTaxeRate() / 100, 2);
                        return $shipping;
                    }
                }
            }            
        }
        //return false;
    }

    public function priceByRegion($stepsRegions, $comparisonValue, $additionnal, $zip, $carrier, $outOfRange)
    {
        $id_region = $this->department->findOneBy(['code_insee' => $zip])->getRegion()->getId();
        foreach ($stepsRegions as $value) {
            if ($comparisonValue >= $value->getStepMin() && $comparisonValue < $value->getStepMax()) {
                foreach ($value->getAmountRegions() as $region) {
                    if ($region->getStepRegion()->getId() == $id_region) {
                        $shipping['price'] = $region->getRegionAmount() + $additionnal;
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping['taxes'] = round($shipping['price'] * $taxe->getTaxeRate() / 100);
                        return $shipping;
                    }
                }
            }
        }
        //si aucun prix n'est retourné par le return :
        //Si le hors tranche est gratuit
        if ($outOfRange == 'free') {
            $shipping['price'] = 0;
            $shipping['taxes'] = 0;
            return $shipping;
        } elseif ($outOfRange == 'hit') {//sinon si le hors tranche prend la valeur la plus haute
            foreach ($stepsRegions as $value) {
                $hit = $value;
            }
            if ($comparisonValue >= $hit->getStepMin() && $comparisonValue < $hit->getStepMax()) {
                foreach ($hit->getAmountRegions() as $region) {
                    if ($region->getStepRegion()->getId() == $id_region) {
                        $shipping['price'] = $region->getRegionAmount() + $additionnal;
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping['taxes'] = round($shipping['price'] * $taxe->getTaxeRate() / 100);
                        return $shipping;
                    }
                }
            }            
        }
    }

    public function priceByCountry($stepsCountries, $comparisonValue, $additionnal, $countryCustomer, $carrier, $outOfRange)
    {
        foreach ($stepsCountries as $value) {
            if ($comparisonValue >= $value->getStepMin() && $comparisonValue < $value->getStepMax()) {
                foreach ($value->getAmountCountries() as $country) {
                    if ($country->getStepCountry()->getId() == $countryCustomer) {
                        $shipping['price'] = $country->getCountryAmount() + $additionnal;
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping['taxes'] = round($shipping['price'] * $taxe->getTaxeRate() / 100);
                        return $shipping;
                    }
                }
            }
        }
        //Si aucun prix n'est retourné par le return :
        //Si le hors tranche est gratuit
        if ($outOfRange == 'free') {
            $shipping['price'] = 0;
            $shipping['taxes'] = 0;
            return $shipping;
        } elseif ($outOfRange == 'hit') {//sinon si le hors tranche prend la valeur la plus haute
            foreach ($stepsRegions as $value) {
                $hit = $value;
            }
            if ($comparisonValue >= $hit->getStepMin() && $comparisonValue < $hit->getStepMax()) {
                foreach ($hit->getAmountCountries() as $country) {
                    if ($country->getStepCountry()->getId() == $countryCustomer) {
                        $shipping['price'] = $country->getCountryAmount() + $additionnal;
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping['taxes'] = round($shipping['price'] * $taxe->getTaxeRate() / 100);
                        return $shipping;
                    }
                }
            }            
        }
    }

    /**
     * Récupère le poids des articles par livreur
     */
    /*public function getWeightProduct()
    {
        //On créée un tableau avec la liste des transporteurs
        $weight = [];
        foreach ($this->carrier->findAll() as $value) {
            $weight[$value->getId()] = 0;
        }
        foreach ($this->cart->cart() as $item) {
            if ($item['product']->getType() != 'downloadable' || $item['product']->getType() != 'downloadable_exclu') {
                if (array_key_exists($item['product']->getCarrier()->getId(), $weight)) {
                    $weight[$item['product']->getCarrier()->getId()] += $item['product']->getWeight();
                }
            }
        }
        return $weight;
    }*/
}