<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCarriersAmountDepartmentsRepository")
 */
class MgCarriersAmountDepartments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriersStepsDep", inversedBy="amountDepartments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier_step;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgDepartmentsFrench", inversedBy="amountDepartments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $step_department;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $department_amount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriersConfig", inversedBy="amountDepartments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier_config;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrierStep(): ?MgCarriersStepsDep
    {
        return $this->carrier_step;
    }

    public function setCarrierStep(?MgCarriersStepsDep $carrier_step): self
    {
        $this->carrier_step = $carrier_step;

        return $this;
    }

    public function getStepDepartment(): ?MgDepartmentsFrench
    {
        return $this->step_department;
    }

    public function setStepDepartment(?MgDepartmentsFrench $step_department): self
    {
        $this->step_department = $step_department;

        return $this;
    }

    public function getDepartmentAmount(): ?float
    {
        return $this->department_amount;
    }

    public function setDepartmentAmount(float $department_amount): self
    {
        $this->department_amount = $department_amount;

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
}
