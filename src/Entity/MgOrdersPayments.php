<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgOrdersPaymentsRepository")
 */
class MgOrdersPayments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgOrders", inversedBy="orderPayments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment_order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgPaymentsModes", inversedBy="orderPayments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment_mode;

    /**
     * @ORM\Column(type="float")
     */
    private $payment_amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $payment_date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $info_transaction;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgOrdersPayments", inversedBy="orderPayments", cascade={"persist", "remove"})
     */
    private $payment_parent;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgOrdersPayments", mappedBy="payment_parent", cascade={"persist", "remove"})
     */
    private $orderPayments;

    public function __construct()
    {
        $this->payment_date = new  \Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentOrder(): ?MgOrders
    {
        return $this->payment_order;
    }

    public function setPaymentOrder(?MgOrders $payment_order): self
    {
        $this->payment_order = $payment_order;

        return $this;
    }

    public function getPaymentMode(): ?MgPaymentsModes
    {
        return $this->payment_mode;
    }

    public function setPaymentMode(?MgPaymentsModes $payment_mode): self
    {
        $this->payment_mode = $payment_mode;

        return $this;
    }

    public function getPaymentAmount(): ?float
    {
        return $this->payment_amount;
    }

    public function setPaymentAmount(float $payment_amount): self
    {
        $this->payment_amount = $payment_amount;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(\DateTimeInterface $payment_date): self
    {
        $this->payment_date = $payment_date;

        return $this;
    }

    public function getInfoTransaction(): ?string
    {
        return $this->info_transaction;
    }

    public function setInfoTransaction(?string $info_transaction): self
    {
        $this->info_transaction = $info_transaction;

        return $this;
    }

    public function getPaymentParent(): ?self
    {
        return $this->payment_parent;
    }

    public function setPaymentParent(?self $payment_parent): self
    {
        $this->payment_parent = $payment_parent;

        return $this;
    }

    public function getOrderPayments(): ?self
    {
        return $this->orderPayments;
    }

    public function setOrderPayments(?self $orderPayments): self
    {
        $this->orderPayments = $orderPayments;

        // set (or unset) the owning side of the relation if necessary
        $newPayment_parent = null === $orderPayments ? null : $this;
        if ($orderPayments->getPaymentParent() !== $newPayment_parent) {
            $orderPayments->setPaymentParent($newPayment_parent);
        }

        return $this;
    }
}
