<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCountriesLangRepository")
 */
class MgCountriesLang
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
     * @ORM\Column(type="string", length=64)
     */
    private $country_name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCountries", inversedBy="countriesLangs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

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

    public function getCountryName(): ?string
    {
        return $this->country_name;
    }

    public function setCountryName(string $country_name): self
    {
        $this->country_name = $country_name;

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
}
