<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCarriersStepsRegionsRepository")
 */
class MgCarriersStepsRegions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriersConfig", inversedBy="stepsRegions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $config;

    /**
     * @ORM\Column(type="float")
     */
    private $step_min;

    /**
     * @ORM\Column(type="float")
     */
    private $step_max;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersAmountRegions", mappedBy="carrier_step", orphanRemoval=true)
     */
    private $amountRegions;

    public function __construct()
    {
        $this->amountRegions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfig(): ?MgCarriersConfig
    {
        return $this->config;
    }

    public function setConfig(?MgCarriersConfig $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function getStepMin(): ?float
    {
        return $this->step_min;
    }

    public function setStepMin(float $step_min): self
    {
        $this->step_min = $step_min;

        return $this;
    }

    public function getStepMax(): ?float
    {
        return $this->step_max;
    }

    public function setStepMax(float $step_max): self
    {
        $this->step_max = $step_max;

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
            $amountRegion->setCarrierStep($this);
        }

        return $this;
    }

    public function removeAmountRegion(MgCarriersAmountRegions $amountRegion): self
    {
        if ($this->amountRegions->contains($amountRegion)) {
            $this->amountRegions->removeElement($amountRegion);
            // set the owning side to null (unless already changed)
            if ($amountRegion->getCarrierStep() === $this) {
                $amountRegion->setCarrierStep(null);
            }
        }

        return $this;
    }
}
