<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgOrdersStatusLangRepository")
 */
class MgOrdersStatusLang
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgLanguages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lang;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgOrders", mappedBy="current_status")
     */
    private $currentOrders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgOrdersStatus", mappedBy="status")
     */
    private $statuses;

    public function __construct()
    {
        $this->currentOrders = new ArrayCollection();
        $this->statuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLang(): ?MgLanguages
    {
        return $this->lang;
    }

    public function setLang(?MgLanguages $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|MgOrders[]
     */
    public function getCurrentOrders(): Collection
    {
        return $this->currentOrders;
    }

    public function addCurrentOrder(MgOrders $currentOrder): self
    {
        if (!$this->currentOrders->contains($currentOrder)) {
            $this->currentOrders[] = $currentOrder;
            $currentOrder->setCurrentStatus($this);
        }

        return $this;
    }

    public function removeCurrentOrder(MgOrders $currentOrder): self
    {
        if ($this->currentOrders->contains($currentOrder)) {
            $this->currentOrders->removeElement($currentOrder);
            // set the owning side to null (unless already changed)
            if ($currentOrder->getCurrentStatus() === $this) {
                $currentOrder->setCurrentStatus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgOrdersStatus[]
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function addStatus(MgOrdersStatus $status): self
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses[] = $status;
            $status->setStatus($this);
        }

        return $this;
    }

    public function removeStatus(MgOrdersStatus $status): self
    {
        if ($this->statuses->contains($status)) {
            $this->statuses->removeElement($status);
            // set the owning side to null (unless already changed)
            if ($status->getStatus() === $this) {
                $status->setStatus(null);
            }
        }

        return $this;
    }
}
