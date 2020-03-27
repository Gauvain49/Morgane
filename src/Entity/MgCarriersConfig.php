<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCarriersConfigRepository")
 */
class MgCarriersConfig
{
    //ENUM de la colonne billing_on
    const PRICE = 'price';
    const WEIGHT = 'weight';
    const QTY = 'qty';
    //ENUM de la colonne out_of_range
    const FREE = 'free';
    const HIT = 'hit';

    private $billingOnValues = array(
        self::PRICE, self::WEIGHT, self::QTY
    );
    private $outOfRangeValues = array(
        self::FREE, self::HIT
    );

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgCarriers", inversedBy="config", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $billing_on;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $out_of_range;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersSteps", mappedBy="config", orphanRemoval=true)
     */
    private $steps;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersAmountCountries", mappedBy="carrier_config", orphanRemoval=true)
     */
    private $amountCountries;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgTaxes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $taxe;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersAmountDepartments", mappedBy="carrier_config", orphanRemoval=true)
     */
    private $amountDepartments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersStepsDep", mappedBy="config", orphanRemoval=true)
     */
    private $stepsDeps;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersStepsRegions", mappedBy="config", orphanRemoval=true)
     */
    private $stepsRegions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersAmountRegions", mappedBy="carrier_config", orphanRemoval=true)
     */
    private $amountRegions;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->amountCountries = new ArrayCollection();
        $this->amountDepartments = new ArrayCollection();
        $this->stepsDeps = new ArrayCollection();
        $this->stepsRegions = new ArrayCollection();
        $this->amountRegions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrier(): ?MgCarriers
    {
        return $this->carrier;
    }

    public function setCarrier(MgCarriers $carrier): self
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getBillingOn(): ?string
    {
        return $this->billing_on;
    }

    public function setBillingOn(string $billing_on): self
    {
        if (!in_array($billing_on, $this->billingOnValues)) {
            throw new \InvalidArgumentException(
                sprintf('Valeur invalide pour mg_carriers_config.billing_on : %s.', $billing_on)
            );
        }
        $this->billing_on = $billing_on;

        return $this;
    }

    public function getOutOfRange(): ?string
    {
        return $this->out_of_range;
    }

    public function setOutOfRange(string $out_of_range): self
    {
        if (!in_array($out_of_range, $this->outOfRangeValues)) {
            throw new \InvalidArgumentException(
                sprintf('Valeur invalide pour mg_carriers_config.out_of_range : %s.', $out_of_range)
            );
        }
        $this->out_of_range = $out_of_range;

        return $this;
    }

    /**
     * @return Collection|MgCarriersSteps[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(MgCarriersSteps $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setConfig($this);
        }

        return $this;
    }

    public function removeStep(MgCarriersSteps $step): self
    {
        if ($this->steps->contains($step)) {
            $this->steps->removeElement($step);
            // set the owning side to null (unless already changed)
            if ($step->getConfig() === $this) {
                $step->setConfig(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgCarriersAmountCountries[]
     */
    public function getAmountCountries(): Collection
    {
        return $this->amountCountries;
    }

    public function addAmountCountry(MgCarriersAmountCountries $amountCountry): self
    {
        if (!$this->amountCountries->contains($amountCountry)) {
            $this->amountCountries[] = $amountCountry;
            $amountCountry->setCarrierConfig($this);
        }

        return $this;
    }

    public function removeAmountCountry(MgCarriersAmountCountries $amountCountry): self
    {
        if ($this->amountCountries->contains($amountCountry)) {
            $this->amountCountries->removeElement($amountCountry);
            // set the owning side to null (unless already changed)
            if ($amountCountry->getCarrierConfig() === $this) {
                $amountCountry->setCarrierConfig(null);
            }
        }

        return $this;
    }

    public function getTaxe(): ?MgTaxes
    {
        return $this->taxe;
    }

    public function setTaxe(?MgTaxes $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    /**
     * @return Collection|MgCarriersAmountDepartments[]
     */
    public function getAmountDepartments(): Collection
    {
        return $this->amountDepartments;
    }

    public function addAmountDepartment(MgCarriersAmountDepartments $amountDepartment): self
    {
        if (!$this->amountDepartments->contains($amountDepartment)) {
            $this->amountDepartments[] = $amountDepartment;
            $amountDepartment->setCarrierConfig($this);
        }

        return $this;
    }

    public function removeAmountDepartment(MgCarriersAmountDepartments $amountDepartment): self
    {
        if ($this->amountDepartments->contains($amountDepartment)) {
            $this->amountDepartments->removeElement($amountDepartment);
            // set the owning side to null (unless already changed)
            if ($amountDepartment->getCarrierConfig() === $this) {
                $amountDepartment->setCarrierConfig(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgCarriersStepsDep[]
     */
    public function getStepsDeps(): Collection
    {
        return $this->stepsDeps;
    }

    public function addStepsDep(MgCarriersStepsDep $stepsDep): self
    {
        if (!$this->stepsDeps->contains($stepsDep)) {
            $this->stepsDeps[] = $stepsDep;
            $stepsDep->setConfig($this);
        }

        return $this;
    }

    public function removeStepsDep(MgCarriersStepsDep $stepsDep): self
    {
        if ($this->stepsDeps->contains($stepsDep)) {
            $this->stepsDeps->removeElement($stepsDep);
            // set the owning side to null (unless already changed)
            if ($stepsDep->getConfig() === $this) {
                $stepsDep->setConfig(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgCarriersStepsRegions[]
     */
    public function getStepsRegions(): Collection
    {
        return $this->stepsRegions;
    }

    public function addStepsRegion(MgCarriersStepsRegions $stepsRegion): self
    {
        if (!$this->stepsRegions->contains($stepsRegion)) {
            $this->stepsRegions[] = $stepsRegion;
            $stepsRegion->setConfig($this);
        }

        return $this;
    }

    public function removeStepsRegion(MgCarriersStepsRegions $stepsRegion): self
    {
        if ($this->stepsRegions->contains($stepsRegion)) {
            $this->stepsRegions->removeElement($stepsRegion);
            // set the owning side to null (unless already changed)
            if ($stepsRegion->getConfig() === $this) {
                $stepsRegion->setConfig(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgCarriersAmountRegions[]
     */
    public function getAmountRegions(): Collection
    {
        return $this->amountRegions;
    }

    public function addAmountRegion(MgCarriersAmountRegions $amountRegion): self
    {
        if (!$this->amountRegions->contains($amountRegion)) {
            $this->amountRegions[] = $amountRegion;
            $amountRegion->setCarrierConfig($this);
        }

        return $this;
    }

    public function removeAmountRegion(MgCarriersAmountRegions $amountRegion): self
    {
        if ($this->amountRegions->contains($amountRegion)) {
            $this->amountRegions->removeElement($amountRegion);
            // set the owning side to null (unless already changed)
            if ($amountRegion->getCarrierConfig() === $this) {
                $amountRegion->setCarrierConfig(null);
            }
        }

        return $this;
    }
}
