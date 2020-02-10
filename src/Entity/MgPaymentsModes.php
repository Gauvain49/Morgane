<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgPaymentsModesRepository")
 */
class MgPaymentsModes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $instructions;

    /**
     * @ORM\Column(type="boolean")
     */
    private $payment_default;

    /**
     * @ORM\Column(type="smallint")
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $link_setting;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $link_submit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgPaymentCheck", mappedBy="mode", orphanRemoval=true)
     */
    private $paymentChecks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgOrdersPayments", mappedBy="payment_mode")
     */
    private $orderPayments;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $type;

    public function __construct()
    {
        $this->paymentChecks = new ArrayCollection();
        $this->orderPayments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(?string $instructions): self
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getPaymentDefault(): ?bool
    {
        return $this->payment_default;
    }

    public function setPaymentDefault(bool $payment_default): self
    {
        $this->payment_default = $payment_default;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLinkSetting(): ?string
    {
        return $this->link_setting;
    }

    public function setLinkSetting(?string $link_setting): self
    {
        $this->link_setting = $link_setting;

        return $this;
    }

    public function getLinkSubmit(): ?string
    {
        return $this->link_submit;
    }

    public function setLinkSubmit(string $link_submit): self
    {
        $this->link_submit = $link_submit;

        return $this;
    }

    /**
     * @return Collection|MgPaymentCheck[]
     */
    public function getPaymentChecks(): Collection
    {
        return $this->paymentChecks;
    }

    public function addPaymentCheck(MgPaymentCheck $paymentCheck): self
    {
        if (!$this->paymentChecks->contains($paymentCheck)) {
            $this->paymentChecks[] = $paymentCheck;
            $paymentCheck->setMode($this);
        }

        return $this;
    }

    public function removePaymentCheck(MgPaymentCheck $paymentCheck): self
    {
        if ($this->paymentChecks->contains($paymentCheck)) {
            $this->paymentChecks->removeElement($paymentCheck);
            // set the owning side to null (unless already changed)
            if ($paymentCheck->getMode() === $this) {
                $paymentCheck->setMode(null);
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
            $orderPayment->setPaymentMode($this);
        }

        return $this;
    }

    public function removeOrderPayment(MgOrdersPayments $orderPayment): self
    {
        if ($this->orderPayments->contains($orderPayment)) {
            $this->orderPayments->removeElement($orderPayment);
            // set the owning side to null (unless already changed)
            if ($orderPayment->getPaymentMode() === $this) {
                $orderPayment->setPaymentMode(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
