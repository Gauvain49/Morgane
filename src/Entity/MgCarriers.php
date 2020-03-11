<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCarriersRepository")
 */
class MgCarriers
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
    private $carrier_name;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $carrier_active;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @Assert\File(mimeTypes={
     *     "image/png",
     *     "image/jpeg",
     *     "image/jpg",
     *     "image/gif",
     * })
     */
    private $carrier_logo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $carrier_default;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCarriersLang", mappedBy="carrier", orphanRemoval=true)
     */
    private $carriersLang;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgCarriersConfig", mappedBy="carrier", cascade={"persist", "remove"})
     */
    private $config;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgProducts", mappedBy="carrier")
     */
    private $products;

    public function __construct()
    {
        $this->carriersLang = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrierName(): ?string
    {
        return $this->carrier_name;
    }

    public function setCarrierName(string $carrier_name): self
    {
        $this->carrier_name = $carrier_name;

        return $this;
    }

    public function getCarrierActive(): ?bool
    {
        return $this->carrier_active;
    }

    public function setCarrierActive(bool $carrier_active): self
    {
        $this->carrier_active = $carrier_active;

        return $this;
    }

    public function getCarrierLogo()
    {
        return $this->carrier_logo;
    }

    public function setCarrierLogo($carrier_logo): self
    {
        $this->carrier_logo = $carrier_logo;

        return $this;
    }

    public function getCarrierDefault(): ?bool
    {
        return $this->carrier_default;
    }

    public function setCarrierDefault(bool $carrier_default): self
    {
        $this->carrier_default = $carrier_default;

        return $this;
    }

    /**
     * @return Collection|MgCarriersLang[]
     */
    public function getCarriersLang(): Collection
    {
        return $this->carriersLang;
    }

    public function addCarriersLang(MgCarriersLang $carriersLang): self
    {
        if (!$this->carriersLang->contains($carriersLang)) {
            $this->carriersLang[] = $carriersLang;
            $carriersLang->setCarrier($this);
        }

        return $this;
    }

    public function removeCarriersLang(MgCarriersLang $carriersLang): self
    {
        if ($this->carriersLang->contains($carriersLang)) {
            $this->carriersLang->removeElement($carriersLang);
            // set the owning side to null (unless already changed)
            if ($carriersLang->getCarrier() === $this) {
                $carriersLang->setCarrier(null);
            }
        }

        return $this;
    }

    public function getConfig(): ?MgCarriersConfig
    {
        return $this->config;
    }

    public function setConfig(MgCarriersConfig $config): self
    {
        $this->config = $config;

        // set the owning side of the relation if necessary
        if ($config->getCarrier() !== $this) {
            $config->setCarrier($this);
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
            $product->setCarrier($this);
        }

        return $this;
    }

    public function removeProduct(MgProducts $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCarrier() === $this) {
                $product->setCarrier(null);
            }
        }

        return $this;
    }
}
