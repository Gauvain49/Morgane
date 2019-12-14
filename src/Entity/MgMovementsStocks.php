<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgMovementsStocksRepository")
 */
class MgMovementsStocks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgProducts", inversedBy="movementsStocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $movement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $info;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_movement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $warning;

    public function __construct()
    {
        $this->date_movement = new \Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?MgProducts
    {
        return $this->product;
    }

    public function setProduct(?MgProducts $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getMovement(): ?int
    {
        return $this->movement;
    }

    public function setMovement(int $movement): self
    {
        $this->movement = $movement;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(string $info): self
    {
        $this->info = $info;

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

    public function getDateMovement(): ?\DateTimeInterface
    {
        return $this->date_movement;
    }

    public function setDateMovement(\DateTimeInterface $date_movement): self
    {
        $this->date_movement = $date_movement;

        return $this;
    }

    public function getWarning(): ?string
    {
        return $this->warning;
    }

    public function setWarning(?string $warning): self
    {
        $this->warning = $warning;

        return $this;
    }
}
