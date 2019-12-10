<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgTaxesRepository")
 */
class MgTaxes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $taxe_rate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $taxe_active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgTaxesLang", mappedBy="taxe", orphanRemoval=true)
     */
    private $taxesLangs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgProducts", mappedBy="taxes")
     */
    private $products;

    public function __construct()
    {
        $this->taxesLangs = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxeRate(): ?float
    {
        return $this->taxe_rate;
    }

    public function setTaxeRate(float $taxe_rate): self
    {
        $this->taxe_rate = $taxe_rate;

        return $this;
    }

    public function getTaxeActive(): ?bool
    {
        return $this->taxe_active;
    }

    public function setTaxeActive(bool $taxe_active): self
    {
        $this->taxe_active = $taxe_active;

        return $this;
    }

    /**
     * @return Collection|MgTaxesLang[]
     */
    public function getTaxesLangs(): Collection
    {
        return $this->taxesLangs;
    }

    public function addTaxesLang(MgTaxesLang $taxesLang): self
    {
        if (!$this->taxesLangs->contains($taxesLang)) {
            $this->taxesLangs[] = $taxesLang;
            $taxesLang->setTaxe($this);
        }

        return $this;
    }

    public function removeTaxesLang(MgTaxesLang $taxesLang): self
    {
        if ($this->taxesLangs->contains($taxesLang)) {
            $this->taxesLangs->removeElement($taxesLang);
            // set the owning side to null (unless already changed)
            if ($taxesLang->getTaxe() === $this) {
                $taxesLang->setTaxe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgProducts[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(MgProducts $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setTaxes($this);
        }

        return $this;
    }

    public function removeProduct(MgProducts $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getTaxes() === $this) {
                $product->setTaxes(null);
            }
        }

        return $this;
    }
}
