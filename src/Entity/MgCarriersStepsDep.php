<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCarriersStepsDepRepository")
 */
class MgCarriersStepsDep
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriersConfig", inversedBy="stepsDeps")
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
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersAmountDepartments", mappedBy="carrier_step", orphanRemoval=true)
     */
    private $amountDepartments;

    public function __construct()
    {
        $this->amountDepartments = new ArrayCollection();
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
            $amountDepartment->setCarrierStep($this);
        }

        return $this;
    }

    public function removeAmountDepartment(MgCarriersAmountDepartments $amountDepartment): self
    {
        if ($this->amountDepartments->contains($amountDepartment)) {
            $this->amountDepartments->removeElement($amountDepartment);
            // set the owning side to null (unless already changed)
            if ($amountDepartment->getCarrierStep() === $this) {
                $amountDepartment->setCarrierStep(null);
            }
        }

        return $this;
    }
}
