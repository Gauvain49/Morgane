<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCustomersAddressesRepository")
 */
class MgCustomersAddresses
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCustomers", inversedBy="addresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgGenders", inversedBy="customersAddresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $address_lastname;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $address_firstname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $address_compagny;

    /**
     * @ORM\Column(type="text")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCountries", inversedBy="customersAddresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="smallint")
     */
    private $type_address;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $name_address;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?MgCustomers
    {
        return $this->customer;
    }

    public function setCustomer(?MgCustomers $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getGender(): ?MgGenders
    {
        return $this->gender;
    }

    public function setGender(?MgGenders $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAddressLastname(): ?string
    {
        return $this->address_lastname;
    }

    public function setAddressLastname(string $address_lastname): self
    {
        $this->address_lastname = $address_lastname;

        return $this;
    }

    public function getAddressFirstname(): ?string
    {
        return $this->address_firstname;
    }

    public function setAddressFirstname(string $address_firstname): self
    {
        $this->address_firstname = $address_firstname;

        return $this;
    }

    public function getAddressCompagny(): ?string
    {
        return $this->address_compagny;
    }

    public function setAddressCompagny(?string $address_compagny): self
    {
        $this->address_compagny = $address_compagny;

        return $this;
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

    public function getCountry(): ?MgCountries
    {
        return $this->country;
    }

    public function setCountry(?MgCountries $country): self
    {
        $this->country = $country;

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

    public function getTypeAddress(): ?int
    {
        return $this->type_address;
    }

    public function setTypeAddress(int $type_address): self
    {
        $this->type_address = $type_address;

        return $this;
    }

    public function getNameAddress(): ?string
    {
        return $this->name_address;
    }

    public function setNameAddress(?string $name_address): self
    {
        $this->name_address = $name_address;

        return $this;
    }
}
