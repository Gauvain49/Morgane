<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCivilitiesRepository")
 */
class MgCivilities
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgLanguages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lang;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $short_civility;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name_civility;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCustomers", mappedBy="gender")
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

    public function getLang(): ?MgLanguages
    {
        return $this->lang;
    }

    public function setLang(?MgLanguages $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getShortCivility(): ?string
    {
        return $this->short_civility;
    }

    public function setShortCivility(string $short_civility): self
    {
        $this->short_civility = $short_civility;

        return $this;
    }

    public function getNameCivility(): ?string
    {
        return $this->name_civility;
    }

    public function setNameCivility(string $name_civility): self
    {
        $this->name_civility = $name_civility;

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
            $customer->setGender($this);
        }

        return $this;
    }

    public function removeCustomer(MgCustomers $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getGender() === $this) {
                $customer->setGender(null);
            }
        }

        return $this;
    }
}
