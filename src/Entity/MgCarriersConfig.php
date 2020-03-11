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

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->amountCountries = new ArrayCollection();
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
        $this->billing_on = $billing_on;

        return $this;
    }

    public function getOutOfRange(): ?string
    {
        return $this->out_of_range;
    }

    public function setOutOfRange(string $out_of_range): self
    {
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
}
