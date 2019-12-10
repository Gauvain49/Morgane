<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgTaxesLangRepository")
 */
class MgTaxesLang
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgTaxes", inversedBy="taxesLangs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $taxe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgLanguages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lang;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $taxe_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxe(): ?MgTaxes
    {
        return $this->taxe;
    }

    public function setTaxe(?MgTaxes $taxe): self
    {
        $this->taxe = $taxe;

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

    public function getTaxeName(): ?string
    {
        return $this->taxe_name;
    }

    public function setTaxeName(string $taxe_name): self
    {
        $this->taxe_name = $taxe_name;

        return $this;
    }
}
