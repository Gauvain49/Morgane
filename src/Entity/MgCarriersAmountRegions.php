<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCarriersAmountRegionsRepository")
 */
class MgCarriersAmountRegions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriersStepsRegions", inversedBy="amountRegions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier_step;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgRegionsFrench")
     * @ORM\JoinColumn(nullable=false)
     */
    private $step_region;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriersConfig", inversedBy="amountRegions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier_config;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $region_amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrierStep(): ?MgCarriersStepsRegions
    {
        return $this->carrier_step;
    }

    public function setCarrierStep(?MgCarriersStepsRegions $carrier_step): self
    {
        $this->carrier_step = $carrier_step;

        return $this;
    }

    public function getStepRegion(): ?MgRegionsFrench
    {
        return $this->step_region;
    }

    public function setStepRegion(?MgRegionsFrench $step_region): self
    {
        $this->step_region = $step_region;

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

    public function getRegionAmount(): ?float
    {
        return $this->region_amount;
    }

    public function setRegionAmount(?float $region_amount): self
    {
        $this->region_amount = $region_amount;

        return $this;
    }
}
