<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgUsersRepository")
 */
class MgUsers implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_up;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgProducts", mappedBy="user")
     */
    private $productAdds;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MgProducts", mappedBy="reviser")
     */
    private $getProductsRevisers;

    public function __construct()
    {
        $this->productAdds = new ArrayCollection();
        $this->getProductsRevisers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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

    public function getDateUp(): ?\DateTimeInterface
    {
        return $this->date_up;
    }

    public function setDateUp(?\DateTimeInterface $date_up): self
    {
        $this->date_up = $date_up;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|MgProducts[]
     */
    public function getProductAdds(): Collection
    {
        return $this->productAdds;
    }

    public function addProductAdd(MgProducts $productAdd): self
    {
        if (!$this->productAdds->contains($productAdd)) {
            $this->productAdds[] = $productAdd;
            $productAdd->setUser($this);
        }

        return $this;
    }

    public function removeProductAdd(MgProducts $productAdd): self
    {
        if ($this->productAdds->contains($productAdd)) {
            $this->productAdds->removeElement($productAdd);
            // set the owning side to null (unless already changed)
            if ($productAdd->getUser() === $this) {
                $productAdd->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgProducts[]
     */
    public function getGetProductsRevisers(): Collection
    {
        return $this->getProductsRevisers;
    }

    public function addGetProductsReviser(MgProducts $getProductsReviser): self
    {
        if (!$this->getProductsRevisers->contains($getProductsReviser)) {
            $this->getProductsRevisers[] = $getProductsReviser;
            $getProductsReviser->addReviser($this);
        }

        return $this;
    }

    public function removeGetProductsReviser(MgProducts $getProductsReviser): self
    {
        if ($this->getProductsRevisers->contains($getProductsReviser)) {
            $this->getProductsRevisers->removeElement($getProductsReviser);
            $getProductsReviser->removeReviser($this);
        }

        return $this;
    }
}
