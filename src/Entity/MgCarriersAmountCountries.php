<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCarriersAmountCountriesRepository")
 */
class MgCarriersAmountCountries
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriersSteps", inversedBy="amountCountries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier_step;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCountries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $step_country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriersConfig", inversedBy="amountCountries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier_config;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $country_amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrierStep(): ?MgCarriersSteps
    {
        return $this->carrier_step;
    }

    public function setCarrierStep(?MgCarriersSteps $carrier_step): self
    {
        $this->carrier_step = $carrier_step;

        return $this;
    }

    public function getStepCountry(): ?MgCountries
    {
        return $this->step_country;
    }

    public function setStepCountry(?MgCountries $step_country): self
    {
        $this->step_country = $step_country;

        return $this;
    }

    public function getCarrierConfig(): ?MgCarriersConfig
    {
        return $this->carrier_config;
    }

    public function setCarrierConfig(?MgCarriersConfig $carrier_config): self
    {
        $this->carrier_config = $carrier_config;

        return $this;
    }

    public function getCountryAmount(): ?float
    {
        return $this->country_amount;
    }

    public function setCountryAmount(?float $country_amount): self
    {
        $this->country_amount = $country_amount;

        return $this;
    }
}
