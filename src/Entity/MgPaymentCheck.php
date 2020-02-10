<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgPaymentCheckRepository")
 */
class MgPaymentCheck
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgPaymentsModes", inversedBy="paymentChecks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $order_check;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address_check;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMode(): ?MgPaymentsModes
    {
        return $this->mode;
    }

    public function setMode(?MgPaymentsModes $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getOrderCheck(): ?string
    {
        return $this->order_check;
    }

    public function setOrderCheck(string $order_check): self
    {
        $this->order_check = $order_check;

        return $this;
    }

    public function getAddressCheck(): ?string
    {
        return $this->address_check;
    }

    public function setAddressCheck(string $address_check): self
    {
        $this->address_check = $address_check;

        return $this;
    }
}
