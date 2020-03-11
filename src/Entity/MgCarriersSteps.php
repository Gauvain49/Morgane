<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCarriersStepsRepository")
 */
class MgCarriersSteps
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriersConfig", inversedBy="steps")
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
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersAmountCountries", mappedBy="carrier_step", orphanRemoval=true)
     */
    private $amountCountries;

    public function __construct()
    {
        $this->amountCountries = new ArrayCollection();
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
            $amountCountry->setCarrierStep($this);
        }

        return $this;
    }

    public function removeAmountCountry(MgCarriersAmountCountries $amountCountry): self
    {
        if ($this->amountCountries->contains($amountCountry)) {
            $this->amountCountries->removeElement($amountCountry);
            // set the owning side to null (unless already changed)
            if ($amountCountry->getCarrierStep() === $this) {
                $amountCountry->setCarrierStep(null);
            }
        }

        return $this;
    }
}
