<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgParametersAddressesTitleRepository")
 */
class MgParametersAddressesTitle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgParametersAddresses", inversedBy="addressesTitles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parameter_address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgLanguages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lang;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address_title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParameterAddress(): ?MgParametersAddresses
    {
        return $this->parameter_address;
    }

    public function setParameterAddress(?MgParametersAddresses $parameter_address): self
    {
        $this->parameter_address = $parameter_address;

        return $this;
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

    public function getAddressTitle(): ?string
    {
        return $this->address_title;
    }

    public function setAddressTitle(string $address_title): self
    {
        $this->address_title = $address_title;

        return $this;
    }
}
