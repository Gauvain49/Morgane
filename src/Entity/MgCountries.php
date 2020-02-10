<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCountriesRepository")
 */
class MgCountries
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $country_default;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $iso_code;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $zip_code_format;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCustomersAddresses", mappedBy="country")
     */
    private $customersAddresses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCountriesLang", mappedBy="country", orphanRemoval=true)
     */
    private $countriesLangs;

    public function __construct()
    {
        $this->customersAddresses = new ArrayCollection();
        $this->countriesLangs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCountryDefault(): ?bool
    {
        return $this->country_default;
    }

    public function setCountryDefault(bool $country_default): self
    {
        $this->country_default = $country_default;

        return $this;
    }

    public function getIsoCode(): ?string
    {
        return $this->iso_code;
    }

    public function setIsoCode(string $iso_code): self
    {
        $this->iso_code = $iso_code;

        return $this;
    }

    public function getZipCodeFormat(): ?string
    {
        return $this->zip_code_format;
    }

    public function setZipCodeFormat(?string $zip_code_format): self
    {
        $this->zip_code_format = $zip_code_format;

        return $this;
    }

    /**
     * @return Collection|MgCustomersAddresses[]
     */
    public function getCustomersAddresses(): Collection
    {
        return $this->customersAddresses;
    }

    public function addCustomersAddress(MgCustomersAddresses $customersAddress): self
    {
        if (!$this->customersAddresses->contains($customersAddress)) {
            $this->customersAddresses[] = $customersAddress;
            $customersAddress->setCountry($this);
        }

        return $this;
    }

    public function removeCustomersAddress(MgCustomersAddresses $customersAddress): self
    {
        if ($this->customersAddresses->contains($customersAddress)) {
            $this->customersAddresses->removeElement($customersAddress);
            // set the owning side to null (unless already changed)
            if ($customersAddress->getCountry() === $this) {
                $customersAddress->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgCountriesLang[]
     */
    public function getCountriesLangs(): Collection
    {
        return $this->countriesLangs;
    }

    public function addCountriesLang(MgCountriesLang $countriesLang): self
    {
        if (!$this->countriesLangs->contains($countriesLang)) {
            $this->countriesLangs[] = $countriesLang;
            $countriesLang->setCountry($this);
        }

        return $this;
    }

    public function removeCountriesLang(MgCountriesLang $countriesLang): self
    {
        if ($this->countriesLangs->contains($countriesLang)) {
            $this->countriesLangs->removeElement($countriesLang);
            // set the owning side to null (unless already changed)
            if ($countriesLang->getCountry() === $this) {
                $countriesLang->setCountry(null);
            }
        }

        return $this;
    }
}
