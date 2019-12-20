<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCustomersRepository")
 */
class MgCustomers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgUsers", inversedBy="customers", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCustomersGroups", inversedBy="customers")
     */
    private $customer_group;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCivilities", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $compagny;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?MgUsers
    {
        return $this->user;
    }

    public function setUser(MgUsers $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCustomerGroup(): ?MgCustomersGroups
    {
        return $this->customer_group;
    }

    public function setCustomerGroup(?MgCustomersGroups $customer_group): self
    {
        $this->customer_group = $customer_group;

        return $this;
    }

    public function getGender(): ?MgCivilities
    {
        return $this->gender;
    }

    public function setGender(?MgCivilities $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCompagny(): ?string
    {
        return $this->compagny;
    }

    public function setCompagny(?string $compagny): self
    {
        $this->compagny = $compagny;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}
