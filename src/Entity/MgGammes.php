<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgGammesRepository")
 */
class MgGammes
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
     * @ORM\OneToMany(targetEntity="App\Entity\MgGammesLang", mappedBy="gamme", orphanRemoval=true)
     */
    private $gammesLangs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgProducts", mappedBy="gamme")
     */
    private $products;

    public function __construct()
    {
        $this->gammesLangs = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    /**
     * @return Collection|MgGammesLang[]
     */
    public function getGammesLangs(): Collection
    {
        return $this->gammesLangs;
    }

    public function addGammesLang(MgGammesLang $gammesLang): self
    {
        if (!$this->gammesLangs->contains($gammesLang)) {
            $this->gammesLangs[] = $gammesLang;
            $gammesLang->setGamme($this);
        }

        return $this;
    }

    public function removeGammesLang(MgGammesLang $gammesLang): self
    {
        if ($this->gammesLangs->contains($gammesLang)) {
            $this->gammesLangs->removeElement($gammesLang);
            // set the owning side to null (unless already changed)
            if ($gammesLang->getGamme() === $this) {
                $gammesLang->setGamme(null);
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
            $product->setGamme($this);
        }

        return $this;
    }

    public function removeProduct(MgProducts $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getGamme() === $this) {
                $product->setGamme(null);
            }
        }

        return $this;
    }
}
