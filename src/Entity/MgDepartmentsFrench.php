<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgDepartmentsFrenchRepository")
 */
class MgDepartmentsFrench
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $code_insee;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersAmountDepartments", mappedBy="step_department")
     */
    private $amountDepartments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgRegionsFrench", inversedBy="departmentsFrenches")
     */
    private $region;

    public function __construct()
    {
        $this->amountDepartments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeInsee(): ?string
    {
        return $this->code_insee;
    }

    public function setCodeInsee(string $code_insee): self
    {
        $this->code_insee = $code_insee;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $amountDepartment->setStepDepartment($this);
        }

        return $this;
    }

    public function removeAmountDepartment(MgCarriersAmountDepartments $amountDepartment): self
    {
        if ($this->amountDepartments->contains($amountDepartment)) {
            $this->amountDepartments->removeElement($amountDepartment);
            // set the owning side to null (unless already changed)
            if ($amountDepartment->getStepDepartment() === $this) {
                $amountDepartment->setStepDepartment(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?MgRegionsFrench
    {
        return $this->region;
    }

    public function setRegion(?MgRegionsFrench $region): self
    {
        $this->region = $region;

        return $this;
    }
}
