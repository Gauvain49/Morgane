<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgRegionsFrenchRepository")
 */
class MgRegionsFrench
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $code_iso;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgDepartmentsFrench", mappedBy="region")
     */
    private $departmentsFrenches;

    public function __construct()
    {
        $this->departmentsFrenches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeIso(): ?string
    {
        return $this->code_iso;
    }

    public function setCodeIso(string $code_iso): self
    {
        $this->code_iso = $code_iso;

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
     * @return Collection|MgDepartmentsFrench[]
     */
    public function getDepartmentsFrenches(): Collection
    {
        return $this->departmentsFrenches;
    }

    public function addDepartmentsFrench(MgDepartmentsFrench $departmentsFrench): self
    {
        if (!$this->departmentsFrenches->contains($departmentsFrench)) {
            $this->departmentsFrenches[] = $departmentsFrench;
            $departmentsFrench->setRegion($this);
        }

        return $this;
    }

    public function removeDepartmentsFrench(MgDepartmentsFrench $departmentsFrench): self
    {
        if ($this->departmentsFrenches->contains($departmentsFrench)) {
            $this->departmentsFrenches->removeElement($departmentsFrench);
            // set the owning side to null (unless already changed)
            if ($departmentsFrench->getRegion() === $this) {
                $departmentsFrench->setRegion(null);
            }
        }

        return $this;
    }
}
