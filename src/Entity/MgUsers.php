<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgUsersRepository")
 * @UniqueEntity(
 *  fields={"username"},
 *  fields={"email"},
 *  message="Cette information est déjà utilisée, merci de la modifier !"
 * )
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
     * @Assert\NotBlank(message="Vous devez choisir un identifiant !")
     */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$#",
     *     match=true,
     *     message="Votre mot de passe n'est pas sécurisé : 8 caractères minimum, au moins une majuscule et un chiffre." 
     * )
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

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MgCustomers", mappedBy="user", cascade={"persist", "remove"})
     */
    private $customers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgPosts", mappedBy="user")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgPostsLang", mappedBy="reviser")
     */
    private $postsLangs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgGenders", inversedBy="users")
     */
    private $gender;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgOrders", mappedBy="user")
     */
    private $orders;

    public function __construct()
    {
        $this->productAdds = new ArrayCollection();
        $this->getProductsRevisers = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->postsLangs = new ArrayCollection();
        $this->date_add = new \Datetime();
        $this->orders = new ArrayCollection();
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

    public function getCompleteName(): ?string
    {
        return $this->firstname . ' ' . $this->lastname;
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
        $roles[] = 'ROLE_ADMIN_USER';
        $roles[] = 'ROLE_VISITOR';

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

    public function getCustomers(): ?MgCustomers
    {
        return $this->customers;
    }

    public function setCustomers(MgCustomers $customers): self
    {
        $this->customers = $customers;

        // set the owning side of the relation if necessary
        if ($customers->getUser() !== $this) {
            $customers->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|MgPosts[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(MgPosts $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(MgPosts $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgPostsLang[]
     */
    public function getPostsLangs(): Collection
    {
        return $this->postsLangs;
    }

    public function addPostsLang(MgPostsLang $postsLang): self
    {
        if (!$this->postsLangs->contains($postsLang)) {
            $this->postsLangs[] = $postsLang;
            $postsLang->setReviser($this);
        }

        return $this;
    }

    public function removePostsLang(MgPostsLang $postsLang): self
    {
        if ($this->postsLangs->contains($postsLang)) {
            $this->postsLangs->removeElement($postsLang);
            // set the owning side to null (unless already changed)
            if ($postsLang->getReviser() === $this) {
                $postsLang->setReviser(null);
            }
        }

        return $this;
    }

    public function getGender(): ?MgGenders
    {
        return $this->gender;
    }

    public function setGender(?MgGenders $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection|MgOrders[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(MgOrders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(MgOrders $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }
}
