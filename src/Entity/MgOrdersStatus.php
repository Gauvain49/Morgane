<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgOrdersStatusRepository")
 */
class MgOrdersStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgOrders", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status_order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgOrdersStatusLang", inversedBy="statuses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    public function __construct()
    {
        $this->date_add = new \Datetime();
   }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatusOrder(): ?MgOrders
    {
        return $this->status_order;
    }

    public function setStatusOrder(?MgOrders $status_order): self
    {
        $this->status_order = $status_order;

        return $this;
    }

    public function getStatus(): ?MgOrdersStatusLang
    {
        return $this->status;
    }

    public function setStatus(?MgOrdersStatusLang $status): self
    {
        $this->status = $status;

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
}
