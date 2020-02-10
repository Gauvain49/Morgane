<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgGendersRepository")
 */
class MgGenders
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
     * @ORM\Column(type="string", length=5)
     */
    private $short_gender;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name_gender;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgUsers", mappedBy="gender")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCustomersAddresses", mappedBy="gender")
     */
    private $customersAddresses;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->customersAddresses = new ArrayCollection();
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

    public function getShortGender(): ?string
    {
        return $this->short_gender;
    }

    public function setShortGender(string $short_gender): self
    {
        $this->short_gender = $short_gender;

        return $this;
    }

    public function getNameGender(): ?string
    {
        return $this->name_gender;
    }

    public function setNameGender(string $name_gender): self
    {
        $this->name_gender = $name_gender;

        return $this;
    }

    /**
     * @return Collection|MgUsers[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(MgUsers $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setGender($this);
        }

        return $this;
    }

    public function removeUser(MgUsers $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getGender() === $this) {
                $user->setGender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgCustomersAddresses[]
     */
    public function getCustomersAddresses(): Collection
    {
        return $this->customersAddresses;
    }

    public function addCustomersAddress(MgCustomersAddresses $customersAddress): self
    {
        if (!$this->customersAddresses->contains($customersAddress)) {
            $this->customersAddresses[] = $customersAddress;
            $customersAddress->setGender($this);
        }

        return $this;
    }

    public function removeCustomersAddress(MgCustomersAddresses $customersAddress): self
    {
        if ($this->customersAddresses->contains($customersAddress)) {
            $this->customersAddresses->removeElement($customersAddress);
            // set the owning side to null (unless already changed)
            if ($customersAddress->getGender() === $this) {
                $customersAddress->setGender(null);
            }
        }

        return $this;
    }
}
