<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgOrdersRepository")
 */
class MgOrders
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgUsers", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $num;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $billing_name;

    /**
     * @ORM\Column(type="text")
     */
    private $billing_address;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $delivery_name;

    /**
     * @ORM\Column(type="text")
     */
    private $delivery_address;

    /**
     * @ORM\Column(type="float")
     */
    private $total_price;

    /**
     * @ORM\Column(type="float")
     */
    private $total_taxes;

    /**
     * @ORM\Column(type="float")
     */
    private $total_price_all_taxes;

    /**
     * @ORM\Column(type="float")
     */
    private $total_shipping_price;

    /**
     * @ORM\Column(type="float")
     */
    private $total_shipping_taxes;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $uniq_key;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgOrdersStatusLang", inversedBy="currentOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $current_status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgOrdersContent", mappedBy="get_order", orphanRemoval=true)
     */
    private $ordersContents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgOrdersPayments", mappedBy="payment_order")
     */
    private $orderPayments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgOrdersStatus", mappedBy="status_order")
     */
    private $orders;

    public function __construct()
    {
        $this->ordersContents = new ArrayCollection();
        $this->orderPayments = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->date_add = new  \Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?MgUsers
    {
        return $this->user;
    }

    public function setUser(?MgUsers $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getBillingName(): ?string
    {
        return $this->billing_name;
    }

    public function setBillingName(string $billing_name): self
    {
        $this->billing_name = $billing_name;

        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billing_address;
    }

    public function setBillingAddress(string $billing_address): self
    {
        $this->billing_address = $billing_address;

        return $this;
    }

    public function getDeliveryName(): ?string
    {
        return $this->delivery_name;
    }

    public function setDeliveryName(string $delivery_name): self
    {
        $this->delivery_name = $delivery_name;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->delivery_address;
    }

    public function setDeliveryAddress(string $delivery_address): self
    {
        $this->delivery_address = $delivery_address;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->total_price;
    }

    public function setTotalPrice(float $total_price): self
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getTotalTaxes(): ?float
    {
        return $this->total_taxes;
    }

    public function setTotalTaxes(float $total_taxes): self
    {
        $this->total_taxes = $total_taxes;

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

    public function getTotalShippingPrice(): ?float
    {
        return $this->total_shipping_price;
    }

    public function setTotalShippingPrice(float $total_shipping_price): self
    {
        $this->total_shipping_price = $total_shipping_price;

        return $this;
    }

    public function getTotalShippingTaxes(): ?float
    {
        return $this->total_shipping_taxes;
    }

    public function setTotalShippingTaxes(float $total_shipping_taxes): self
    {
        $this->total_shipping_taxes = $total_shipping_taxes;

        return $this;
    }

    public function getUniqKey(): ?string
    {
        return $this->uniq_key;
    }

    public function setUniqKey(string $uniq_key): self
    {
        $this->uniq_key = $uniq_key;

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

    public function getCurrentStatus(): ?MgOrdersStatusLang
    {
        return $this->current_status;
    }

    public function setCurrentStatus(?MgOrdersStatusLang $current_status): self
    {
        $this->current_status = $current_status;

        return $this;
    }

    /**
     * @return Collection|MgOrdersContent[]
     */
    public function getOrdersContents(): Collection
    {
        return $this->ordersContents;
    }

    public function addOrdersContent(MgOrdersContent $ordersContent): self
    {
        if (!$this->ordersContents->contains($ordersContent)) {
            $this->ordersContents[] = $ordersContent;
            $ordersContent->setGetOrder($this);
        }

        return $this;
    }

    public function removeOrdersContent(MgOrdersContent $ordersContent): self
    {
        if ($this->ordersContents->contains($ordersContent)) {
            $this->ordersContents->removeElement($ordersContent);
            // set the owning side to null (unless already changed)
            if ($ordersContent->getGetOrder() === $this) {
                $ordersContent->setGetOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgOrdersPayments[]
     */
    public function getOrderPayments(): Collection
    {
        return $this->orderPayments;
    }

    public function addOrderPayment(MgOrdersPayments $orderPayment): self
    {
        if (!$this->orderPayments->contains($orderPayment)) {
            $this->orderPayments[] = $orderPayment;
            $orderPayment->setPaymentOrder($this);
        }

        return $this;
    }

    public function removeOrderPayment(MgOrdersPayments $orderPayment): self
    {
        if ($this->orderPayments->contains($orderPayment)) {
            $this->orderPayments->removeElement($orderPayment);
            // set the owning side to null (unless already changed)
            if ($orderPayment->getPaymentOrder() === $this) {
                $orderPayment->setPaymentOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgOrdersStatus[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(MgOrdersStatus $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setStatusOrder($this);
        }

        return $this;
    }

    public function removeOrder(MgOrdersStatus $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getStatusOrder() === $this) {
                $order->setStatusOrder(null);
            }
        }

        return $this;
    }
}
