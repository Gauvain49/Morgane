<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgOrdersContentRepository")
 */
class MgOrdersContent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgOrders", inversedBy="ordersContents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $get_order;

    /**
     * @ORM\Column(type="integer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $format;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $designation;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $gross_unit_price;

    /**
     * @ORM\Column(type="float")
     */
    private $gross_unit_tax;

    /**
     * @ORM\Column(type="float")
     */
    private $gross_unit_price_all_taxes;

    /**
     * @ORM\Column(type="float")
     */
    private $amount_discount;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $nature_discount = [];

    /**
     * @ORM\Column(type="float")
     */
    private $net_unit_price;

    /**
     * @ORM\Column(type="float")
     */
    private $net_unit_tax;

    /**
     * @ORM\Column(type="float")
     */
    private $net_unit_price_all_taxes;

    /**
     * @ORM\Column(type="float")
     */
    private $total_net_price;

    /**
     * @ORM\Column(type="float")
     */
    private $total_net_tax;

    /**
     * @ORM\Column(type="float")
     */
    private $total_price_all_taxes;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgOrdersTaxes", mappedBy="order_content", cascade={"persist", "remove"})
     */
    private $taxes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGetOrder(): ?MgOrders
    {
        return $this->get_order;
    }

    public function setGetOrder(?MgOrders $get_order): self
    {
        $this->get_order = $get_order;

        return $this;
    }

    public function getProduct(): ?int
    {
        return $this->product;
    }

    public function setProduct(?int $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getGrossUnitPrice(): ?float
    {
        return $this->gross_unit_price;
    }

    public function setGrossUnitPrice(float $gross_unit_price): self
    {
        $this->gross_unit_price = $gross_unit_price;

        return $this;
    }

    public function getGrossUnitTax(): ?float
    {
        return $this->gross_unit_tax;
    }

    public function setGrossUnitTax(float $gross_unit_tax): self
    {
        $this->gross_unit_tax = $gross_unit_tax;

        return $this;
    }

    public function getGrossUnitPriceAllTaxes(): ?float
    {
        return $this->gross_unit_price_all_taxes;
    }

    public function setGrossUnitPriceAllTaxes(float $gross_unit_price_all_taxes): self
    {
        $this->gross_unit_price_all_taxes = $gross_unit_price_all_taxes;

        return $this;
    }

    public function getAmountDiscount(): ?float
    {
        return $this->amount_discount;
    }

    public function setAmountDiscount(float $amount_discount): self
    {
        $this->amount_discount = $amount_discount;

        return $this;
    }

    public function getNatureDiscount(): ?array
    {
        return $this->nature_discount;
    }

    public function setNatureDiscount(?array $nature_discount): self
    {
        $this->nature_discount = $nature_discount;

        return $this;
    }

    public function getNetUnitPrice(): ?float
    {
        return $this->net_unit_price;
    }

    public function setNetUnitPrice(float $net_unit_price): self
    {
        $this->net_unit_price = $net_unit_price;

        return $this;
    }

    public function getNetUnitTax(): ?float
    {
        return $this->net_unit_tax;
    }

    public function setNetUnitTax(float $net_unit_tax): self
    {
        $this->net_unit_tax = $net_unit_tax;

        return $this;
    }

    public function getNetUnitPriceAllTaxes(): ?float
    {
        return $this->net_unit_price_all_taxes;
    }

    public function setNetUnitPriceAllTaxes(float $net_unit_price_all_taxes): self
    {
        $this->net_unit_price_all_taxes = $net_unit_price_all_taxes;

        return $this;
    }

    public function getTotalNetPrice(): ?float
    {
        return $this->total_net_price;
    }

    public function setTotalNetPrice(float $total_net_price): self
    {
        $this->total_net_price = $total_net_price;

        return $this;
    }

    public function getTotalNetTax(): ?float
    {
        return $this->total_net_tax;
    }

    public function setTotalNetTax(float $total_net_tax): self
    {
        $this->total_net_tax = $total_net_tax;

        return $this;
    }

    public function getTotalPriceAllTaxes(): ?float
    {
        return $this->total_price_all_taxes;
    }

    public function setTotalPriceAllTaxes(float $total_price_all_taxes): self
    {
        $this->total_price_all_taxes = $total_price_all_taxes;

        return $this;
    }

    public function getTaxes(): ?MgOrdersTaxes
    {
        return $this->taxes;
    }

    public function setTaxes(MgOrdersTaxes $taxes): self
    {
        $this->taxes = $taxes;

        // set the owning side of the relation if necessary
        if ($taxes->getOrderContent() !== $this) {
            $taxes->setOrderContent($this);
        }

        return $this;
    }
}
