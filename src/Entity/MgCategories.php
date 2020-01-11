<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgCategoriesRepository")
 */
class MgCategories
{

    //ENUM de la colonne type
    const TYPE_POST = 'post';
    const TYPE_PRODUCT = 'product';

    private $typeValues = array(
        self::TYPE_POST, self::TYPE_PRODUCT
    );

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgCategories", inversedBy="children")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCategories", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $force_display;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_up;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgCategoryLang", mappedBy="cat", orphanRemoval=true)
     */
    private $contents;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MgProducts", mappedBy="categories")
     */
    private $products;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MgPosts", mappedBy="categories")
     */
    private $posts;

    public function __construct()
    {
        $this->date_add = new \Datetime();
        $this->children = new ArrayCollection();
        $this->contents = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

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

    public function getForceDisplay(): ?bool
    {
        return $this->force_display;
    }

    public function setForceDisplay(bool $force_display): self
    {
        $this->force_display = $force_display;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if (!in_array($type, $this->typeValues)) {
            throw new \InvalidArgumentException(
                sprintf('Valeur invalide pour mg_categorie.type : %s.', $type)
            );
        }
        $this->type = $type;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|MgCategoryLang[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(MgCategoryLang $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setCat($this);
        }

        return $this;
    }

    public function removeContent(MgCategoryLang $content): self
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            // set the owning side to null (unless already changed)
            if ($content->getCat() === $this) {
                $content->setCat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgProducts[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(MgProducts $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(MgProducts $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeCategory($this);
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
            $post->addCategory($this);
        }

        return $this;
    }

    public function removePost(MgPosts $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeCategory($this);
        }

        return $this;
    }
}
