<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgParametersAddressesRepository")
 */
class MgParametersAddresses
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
    private $address;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $map;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $opening_time;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgParametersAddressesTitle", mappedBy="parameter_address", orphanRemoval=true)
     */
    private $addressesTitles;

    public function __construct()
    {
        $this->addressesTitles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMap(): ?string
    {
        return $this->map;
    }

    public function setMap(?string $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getOpeningTime(): ?string
    {
        return $this->opening_time;
    }

    public function setOpeningTime(?string $opening_time): self
    {
        $this->opening_time = $opening_time;

        return $this;
    }

    /**
     * @return Collection|MgParametersAddressesTitle[]
     */
    public function getAddressesTitles(): Collection
    {
        return $this->addressesTitles;
    }

    public function addAddressesTitle(MgParametersAddressesTitle $addressesTitle): self
    {
        if (!$this->addressesTitles->contains($addressesTitle)) {
            $this->addressesTitles[] = $addressesTitle;
            $addressesTitle->setParameterAddress($this);
        }

        return $this;
    }

    public function removeAddressesTitle(MgParametersAddressesTitle $addressesTitle): self
    {
        if ($this->addressesTitles->contains($addressesTitle)) {
            $this->addressesTitles->removeElement($addressesTitle);
            // set the owning side to null (unless already changed)
            if ($addressesTitle->getParameterAddress() === $this) {
                $addressesTitle->setParameterAddress(null);
            }
        }

        return $this;
    }
}
