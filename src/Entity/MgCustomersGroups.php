<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCustomersGroupsRepository")
 */
class MgCustomersGroups
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $group_name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $group_discount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCustomers", mappedBy="customer_group")
     */
    private $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(string $group_name): self
    {
        $this->group_name = $group_name;

        return $this;
    }

    public function getGroupDiscount(): ?float
    {
        return $this->group_discount;
    }

    public function setGroupDiscount(?float $group_discount): self
    {
        $this->group_discount = $group_discount;

        return $this;
    }

    /**
     * @return Collection|MgCustomers[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(MgCustomers $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setCustomerGroup($this);
        }

        return $this;
    }

    public function removeCustomer(MgCustomers $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getCustomerGroup() === $this) {
                $customer->setCustomerGroup(null);
            }
        }

        return $this;
    }
}
