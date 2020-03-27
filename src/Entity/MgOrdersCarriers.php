<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgOrdersCarriersRepository")
 */
class MgOrdersCarriers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgOrders", inversedBy="orderCarriers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $get_order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCarriers", inversedBy="orderCarriers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier;

    /**
     * @ORM\Column(type="float")
     */
    private $shipping_cost_tax_excl;

    /**
     * @ORM\Column(type="float")
     */
    private $shipping_cost_taxes;

    /**
     * @ORM\Column(type="date")
     */
    private $date_add;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tracking_number;

    public function __construct()
    {
        $this->date_add = new \Datetime();
    }

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

    public function getCarrier(): ?MgCarriers
    {
        return $this->carrier;
    }

    public function setCarrier(?MgCarriers $carrier): self
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getShippingCostTaxExcl(): ?float
    {
        return $this->shipping_cost_tax_excl;
    }

    public function setShippingCostTaxExcl(?float $shipping_cost_tax_excl): self
    {
        $this->shipping_cost_tax_excl = $shipping_cost_tax_excl;

        return $this;
    }

    public function getShippingCostTaxes(): ?float
    {
        return $this->shipping_cost_taxes;
    }

    public function setShippingCostTaxes(float $shipping_cost_taxes): self
    {
        $this->shipping_cost_taxes = $shipping_cost_taxes;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->tracking_number;
    }

    public function setTrackingNumber(?string $tracking_number): self
    {
        $this->tracking_number = $tracking_number;

        return $this;
    }
}
