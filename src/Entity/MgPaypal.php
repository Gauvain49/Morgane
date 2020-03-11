<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgPaypalRepository")
 */
class MgPaypal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgPaymentsModes", inversedBy="paypal", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $mode;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $paypal_method;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mode_test;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $user_test;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $password_test;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $signature_test;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $link_test;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $signature;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $link;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMode(): ?MgPaymentsModes
    {
        return $this->mode;
    }

    public function setMode(MgPaymentsModes $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getPaypalMethod(): ?string
    {
        return $this->paypal_method;
    }

    public function setPaypalMethod(?string $paypal_method): self
    {
        $this->paypal_method = $paypal_method;

        return $this;
    }

    public function getModeTest(): ?bool
    {
        return $this->mode_test;
    }

    public function setModeTest(bool $mode_test): self
    {
        $this->mode_test = $mode_test;

        return $this;
    }

    public function getUserTest(): ?string
    {
        return $this->user_test;
    }

    public function setUserTest(?string $user_test): self
    {
        $this->user_test = $user_test;

        return $this;
    }

    public function getPasswordTest(): ?string
    {
        return $this->password_test;
    }

    public function setPasswordTest(?string $password_test): self
    {
        $this->password_test = $password_test;

        return $this;
    }

    public function getSignatureTest(): ?string
    {
        return $this->signature_test;
    }

    public function setSignatureTest(?string $signature_test): self
    {
        $this->signature_test = $signature_test;

        return $this;
    }

    public function getLinkTest(): ?string
    {
        return $this->link_test;
    }

    public function setLinkTest(?string $link_test): self
    {
        $this->link_test = $link_test;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
