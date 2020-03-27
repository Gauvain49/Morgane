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

    public function getShipping($countryCustomer, $zip)
    {
        if (!$this->session->has('shipping')) {
            $this->session->set('shipping', []);
        }
        //Nombre d'article soumis au frais de livraison (ne comprends pas les articles numériques)
        $submittedToTheDelivery = 0;
        //$shipping = ['price' => 0, 'taxes' => 0, 'carrier' => null];
        $shipping = [];
        foreach ($this->cart->cart() as $item) {
            if ($item['product']->getType() != 'downloadable' || $item['product']->getType() != 'downloadable_exclu') {
                $carrier = $this->carrier->find($item['product']->getCarrier()->getId());
                $tranche = false;//Variable permettant de vérifier si une tranche est trouvée
                if ($carrier->getConfig()->getBillingOn() == 'qty') {
                    //Si le client a une livraison en France, le prix peut dépendre de son département, sa région ou son pays (France)
                    if ($countryCustomer == 8) {
                        //On vérifie d'abord s'il y a un prix au département
                        if (count($carrier->getConfig()->getStepsDeps()) > 0) {
                            $shipping = $this->priceByDepartment($carrier->getConfig()->getStepsDeps(), $this->cart->totalQuantity(), $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                            $this->setShipping($shipping);
                            return $shipping;

                        } elseif(count($carrier->getConfig()->getStepsRegions()) > 0) {// Sinon s'il y a un prix à la région
                            $shipping = $this->priceByRegion($carrier->getConfig()->getStepsRegions(), $this->cart->totalQuantity(), $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                            $this->setShipping($shipping);
                            //dd($shipping);
                            return $shipping;
                        } elseif(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping = $this->priceByCountry($carrier->getConfig()->getSteps(), $this->cart->totalQuantity(), $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                            $this->setShipping($shipping);
                            //dd($shipping);
                            return $shipping;
                        }
                    } else {//Sinon il dépend uniquement du pays
                        if(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping = $this->priceByCountry($carrier->getConfig()->getSteps(), $this->cart->totalQuantity(), $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    }
                } elseif($carrier->getConfig()->getBillingOn() == 'price') {
                    //Si le client a une livraison en France, le prix peut dépendre de son département, sa région ou son pays (France)
                    if ($countryCustomer == 8) {
                        //On vérifie d'abord s'il y a un prix au département
                        if (count($carrier->getConfig()->getStepsDeps()) > 0) {
                            $shipping = $this->priceByDepartment($carrier->getConfig()->getStepsDeps(), $this->cart->totalCart(), $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                        } elseif(count($carrier->getConfig()->getStepsRegions()) > 0) {// Sinon s'il y a un prix à la région
                            $shipping = $this->priceByRegion($carrier->getConfig()->getStepsRegions(), $this->cart->totalCart(), $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                        } elseif(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping = $this->priceByCountry($carrier->getConfig()->getSteps(), $this->cart->totalCart(), $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    } else {//Sinon il dépend uniquement du pays
                        if(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping = $this->priceByCountry($carrier->getConfig()->getSteps(), $this->cart->totalCart(), $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    }
                } elseif($carrier->getConfig()->getBillingOn() == 'weight') {
                    $weight = $this->getWeightProduct();
                    //Si le client a une livraison en France, le prix peut dépendre de son département, sa région ou son pays (France)
                    if ($countryCustomer == 8) {
                        //On vérifie d'abord s'il y a un prix au département
                        if (count($carrier->getConfig()->getStepsDeps()) > 0) {
                            $shipping = $this->priceByDepartment($carrier->getConfig()->getStepsDeps(), $weight[$carrier->getId], $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                        } elseif(count($carrier->getConfig()->getStepsRegions()) > 0) {// Sinon s'il y a un prix à la région
                            $shipping = $this->priceByRegion($carrier->getConfig()->getStepsRegions(), $weight[$carrier->getId], $zip, $carrier, $carrier->getConfig()->getOutOfRange());
                        } elseif(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping = $this->priceByCountry($carrier->getConfig()->getSteps(), $weight[$carrier->getId], $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    } else {//Sinon il dépend uniquement du pays
                        if(count($carrier->getConfig()->getSteps()) > 0) {// Sinon s'il y a un prix au pays
                            $shipping = $this->priceByCountry($carrier->getConfig()->getSteps(), $weight[$carrier->getId], $countryCustomer, $carrier, $carrier->getConfig()->getOutOfRange());
                        }
                    }
                }
                //Si le hors tranche (out of range) est à hit, on prend la tranche la plus haute
                if ($carrier->getConfig()->getOutOfRange() == 'hit') {
                    foreach ($value->getAmountCountries() as $country) {
                        if ($country->getStepCountry()->getId() == $countryCustomer) {
                            $shipping[$carrier->getId()]['price'] = $country->getCountryAmount();
                                $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                            $shipping[$carrier->getId()]['taxes'] = $shipping[$carrier->getId()]['price'] * $taxe->getTaxeRate() / 100;
                        }
                    }
                } else {//Sinon on laisse le port à 0 comme initialisé een début de script
                    $shipping[$carrier->getId()]['price'] = 0;
                    $shipping[$carrier->getId()]['taxes'] = 0;
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

    public function priceByDepartment($stepsDeps, $comparisonValue, $zip, $carrier, $outOfRange)
    {
        //On déclare la variable recueillant le prix de la livraison
        $shipping = [];
        foreach ($stepsDeps as $value) {
            if ($comparisonValue >= $value->getStepMin() && $comparisonValue < $value->getStepMax()) {
                foreach ($value->getAmountDepartments() as $department) {
                    if ($zip == $this->department->find($department->getStepDepartment())->getCodeInsee()) {
                        $shipping[$carrier->getId()]['price'] = $department->getDepartmentAmount();
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping[$carrier->getId()]['taxes'] = round($shipping[$carrier->getId()]['price'] * $taxe->getTaxeRate() / 100, 2);
                        return $shipping;
                    }
                }
            }
        }
        //si aucun prix n'est retourné par le return :
        //Si le hors tranche est gratuit
        if ($outOfRange == 'free') {
            $shipping[$carrier->getId()]['price'] = 0;
            $shipping[$carrier->getId()]['taxes'] = 0;
            return $shipping;
        } elseif ($outOfRange == 'hit') {//sinon si le hors tranche prend la valeur la plus haute
            foreach ($stepsDeps as $value) {
                $hit = $value;
            }
            if ($comparisonValue >= $hit->getStepMin() && $comparisonValue < $hit->getStepMax()) {
                foreach ($hit->getAmountDepartments() as $department) {
                    if ($zip == $this->department->find($department->getStepDepartment())->getCodeInsee()) {
                        $shipping[$carrier->getId()]['price'] = $department->getDepartmentAmount();
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping[$carrier->getId()]['taxes'] = round($shipping[$carrier->getId()]['price'] * $taxe->getTaxeRate() / 100, 2);
                        return $shipping;
                    }
                }
            }            
        }
        //return false;
    }

    public function priceByRegion($stepsRegions, $comparisonValue, $zip, $carrier, $outOfRange)
    {
        $id_region = $this->department->findOneBy(['code_insee' => $zip])->getRegion()->getId();
        foreach ($stepsRegions as $value) {
            if ($comparisonValue >= $value->getStepMin() && $comparisonValue < $value->getStepMax()) {
                foreach ($value->getAmountRegions() as $region) {
                    if ($region->getStepRegion()->getId() == $id_region) {
                        $shipping[$carrier->getId()]['price'] = $region->getRegionAmount();
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping[$carrier->getId()]['taxes'] = round($shipping[$carrier->getId()]['price'] * $taxe->getTaxeRate() / 100);
                        return $shipping;
                    }
                }
            }
        }
        //si aucun prix n'est retourné par le return :
        //Si le hors tranche est gratuit
        if ($outOfRange == 'free') {
            $shipping[$carrier->getId()]['price'] = 0;
            $shipping[$carrier->getId()]['taxes'] = 0;
            return $shipping;
        } elseif ($outOfRange == 'hit') {//sinon si le hors tranche prend la valeur la plus haute
            foreach ($stepsRegions as $value) {
                $hit = $value;
            }
            if ($comparisonValue >= $hit->getStepMin() && $comparisonValue < $hit->getStepMax()) {
                foreach ($hit->getAmountRegions() as $region) {
                    if ($region->getStepRegion()->getId() == $id_region) {
                        $shipping[$carrier->getId()]['price'] = $region->getRegionAmount();
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping[$carrier->getId()]['taxes'] = round($shipping[$carrier->getId()]['price'] * $taxe->getTaxeRate() / 100);
                        return $shipping;
                    }
                }
            }            
        }
    }

    public function priceByCountry($stepsCountries, $comparisonValue, $countryCustomer, $carrier, $outOfRange)
    {
        foreach ($stepsCountries as $value) {
            if ($comparisonValue >= $value->getStepMin() && $comparisonValue < $value->getStepMax()) {
                foreach ($value->getAmountCountries() as $country) {
                    if ($country->getStepCountry()->getId() == $countryCustomer) {
                        $shipping[$carrier->getId()]['price'] = $country->getCountryAmount();
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping[$carrier->getId()]['taxes'] = round($shipping[$carrier->getId()]['price'] * $taxe->getTaxeRate() / 100);
                        return $shipping;
                    }
                }
            }
        }
        //Si aucun prix n'est retourné par le return :
        //Si le hors tranche est gratuit
        if ($outOfRange == 'free') {
            $shipping[$carrier->getId()]['price'] = 0;
            $shipping[$carrier->getId()]['taxes'] = 0;
            return $shipping;
        } elseif ($outOfRange == 'hit') {//sinon si le hors tranche prend la valeur la plus haute
            foreach ($stepsRegions as $value) {
                $hit = $value;
            }
            if ($comparisonValue >= $hit->getStepMin() && $comparisonValue < $hit->getStepMax()) {
                foreach ($hit->getAmountCountries() as $country) {
                    if ($country->getStepCountry()->getId() == $countryCustomer) {
                        $shipping[$carrier->getId()]['price'] = $country->getCountryAmount();
                        $taxe = $this->taxes->find($carrier->getConfig()->getTaxe()->getId());
                        $shipping[$carrier->getId()]['taxes'] = round($shipping[$carrier->getId()]['price'] * $taxe->getTaxeRate() / 100);
                        return $shipping;
                    }
                }
            }            
        }
    }

    /**
     * Récupère le poids des articles par livreur
     */
    public function getWeightProduct()
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
    }
}