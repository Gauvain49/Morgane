<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgOrdersTaxesRepository")
 */
class MgOrdersTaxes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgOrdersContent", inversedBy="taxes", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $order_content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgTaxes", inversedBy="orderContentTaxes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $taxe;

    /**
     * @ORM\Column(type="float")
     */
    private $base_price;

    /**
     * @ORM\Column(type="float")
     */
    private $unit_tax;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $total_tax;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderContent(): ?MgOrdersContent
    {
        return $this->order_content;
    }

    public function setOrderContent(MgOrdersContent $order_content): self
    {
        $this->order_content = $order_content;

        return $this;
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

    public function getBasePrice(): ?float
    {
        return $this->base_price;
    }

    public function setBasePrice(float $base_price): self
    {
        $this->base_price = $base_price;

        return $this;
    }

    public function getUnitTax(): ?float
    {
        return $this->unit_tax;
    }

    public function setUnitTax(float $unit_tax): self
    {
        $this->unit_tax = $unit_tax;

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

    public function getTotalTax(): ?float
    {
        return $this->total_tax;
    }

    public function setTotalTax(float $total_tax): self
    {
        $this->total_tax = $total_tax;

        return $this;
    }
}
