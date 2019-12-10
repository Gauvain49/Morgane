<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgProductsRepository")
 */
class MgProducts
{
    //ENUM de la colonne discount_type
    const DISCOUNT_ON_AMOUNT = 'amount';
    const DISCOUNT_ON_PERCENT = 'percent';

    //ENUM de la colonne type
    const TYPE_MASTER = 'master';
    const TYPE_DOWNLOADABLE = 'downloadable';
    const TYPE_ATTRIBUT = 'attribut';

    private $discountTypeValues = array(
        self::DISCOUNT_ON_AMOUNT, self::DISCOUNT_ON_PERCENT
    );
    private $typeValues = array(
        self::TYPE_MASTER, self::TYPE_DOWNLOADABLE, self::TYPE_ATTRIBUT
    );

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $purshasing_price;

    /**
     * @ORM\Column(type="float")
     */
    private $selling_price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgTaxes", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $taxe;

    /**
     * @ORM\Column(type="float")
     */
    private $selling_price_all_taxes;

    /**
     * @ORM\Column(type="integer")
     */
    private $sales_unit;

    /**
     * @ORM\Column(type="integer")
     */
    private $min_quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bulk_quantity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $discount_type;

    /**
     * @ORM\Column(type="boolean", options={"default": false}, nullable=true)
     */
    private $discount_on_taxe;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $stock_management;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="boolean", options={"default": false}, nullable=true)
     */
    private $sell_out_of_stock;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock_alert;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     */
    private $pre_order;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $available_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_publish;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $offline;

    /**
     * @ORM\Column(type="string", length=50)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\MgUsers", inversedBy="productAdds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MgUsers", inversedBy="getProductsRevisers")
     */
    private $reviser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgSuppliers", inversedBy="products")
     */
    private $supplier;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgProductsLang", mappedBy="product", orphanRemoval=true)
     */
    private $contents;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgGammes", inversedBy="products")
     */
    private $gamme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgProducts", inversedBy="mgProducts")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgProducts", mappedBy="parent")
     */
    private $mgProducts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MgCategories", inversedBy="products")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgProductsImages", mappedBy="product", orphanRemoval=true)
     */
    private $images;

    public function __construct()
    {
        $this->reviser = new ArrayCollection();
        $this->date_add = new \Datetime();
        $this->available_date = new \Datetime();
        $this->date_publish = new \Datetime();
        $this->type = self::TYPE_MASTER;
        $this->contents = new ArrayCollection();
        $this->mgProducts = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPurshasingPrice(): ?float
    {
        return $this->purshasing_price;
    }

    public function setPurshasingPrice(?float $purshasing_price): self
    {
        $this->purshasing_price = $purshasing_price;

        return $this;
    }

    public function getSellingPrice(): ?float
    {
        return $this->selling_price;
    }

    public function setSellingPrice(float $selling_price): self
    {
        $this->selling_price = $selling_price;

        return $this;
    }

    public function getTaxe(): ?MgTaxes
    {
        return $this->taxe;
    }

    public function setTaxe(?MgTaxes $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getSellingPriceAllTaxes(): ?float
    {
        return $this->selling_price_all_taxes;
    }

    public function setSellingPriceAllTaxes(float $selling_price_all_taxes): self
    {
        $this->selling_price_all_taxes = $selling_price_all_taxes;

        return $this;
    }

    public function getSalesUnit(): ?int
    {
        return $this->sales_unit;
    }

    public function setSalesUnit(int $sales_unit): self
    {
        $this->sales_unit = $sales_unit;

        return $this;
    }

    public function getMinQuantity(): ?int
    {
        return $this->min_quantity;
    }

    public function setMinQuantity(int $min_quantity): self
    {
        $this->min_quantity = $min_quantity;

        return $this;
    }

    public function getMaxQuantity(): ?int
    {
        return $this->max_quantity;
    }

    public function setMaxQuantity(?int $max_quantity): self
    {
        $this->max_quantity = $max_quantity;

        return $this;
    }

    public function getBulkQuantity(): ?int
    {
        return $this->bulk_quantity;
    }

    public function setBulkQuantity(?int $bulk_quantity): self
    {
        $this->bulk_quantity = $bulk_quantity;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscountType(): ?string
    {
        return $this->discount_type;
    }

    public function setDiscountType(?string $discount_type): self
    {
        if (!in_array($discount_type, $this->discountTypeValues)) {
            throw new \InvalidArgumentException(
                sprintf('Valeur invalide pour mg_products.discount_type : %s.', $discount_type)
            );
        }
        $this->discount_type = $discount_type;

        return $this;
    }

    public function getDiscountOnTaxe(): ?bool
    {
        return $this->discount_on_taxe;
    }

    public function setDiscountOnTaxe(?bool $discount_on_taxe): self
    {
        $this->discount_on_taxe = $discount_on_taxe;

        return $this;
    }

    public function getStockManagement(): ?bool
    {
        return $this->stock_management;
    }

    public function setStockManagement(bool $stock_management): self
    {
        $this->stock_management = $stock_management;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSellOutOfStock(): ?bool
    {
        return $this->sell_out_of_stock;
    }

    public function setSellOutOfStock(?bool $sell_out_of_stock): self
    {
        $this->sell_out_of_stock = $sell_out_of_stock;

        return $this;
    }

    public function getStockAlert(): ?int
    {
        return $this->stock_alert;
    }

    public function setStockAlert(?int $stock_alert): self
    {
        $this->stock_alert = $stock_alert;

        return $this;
    }

    public function getPreOrder(): ?bool
    {
        return $this->pre_order;
    }

    public function setPreOrder(?bool $pre_order): self
    {
        $this->pre_order = $pre_order;

        return $this;
    }

    public function getAvailableDate(): ?\DateTimeInterface
    {
        return $this->available_date;
    }

    public function setAvailableDate(?\DateTimeInterface $available_date): self
    {
        $this->available_date = $available_date;

        return $this;
    }

    public function getDatePublish(): ?\DateTimeInterface
    {
        return $this->date_publish;
    }

    public function setDatePublish(?\DateTimeInterface $date_publish): self
    {
        $this->date_publish = $date_publish;

        return $this;
    }

    public function getOffline(): ?bool
    {
        return $this->offline;
    }

    public function setOffline(bool $offline): self
    {
        $this->offline = $offline;

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
                sprintf('Valeur invalide pour mg_products.type : %s.', $type)
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

    public function getUser(): ?MgUsers
    {
        return $this->user;
    }

    public function setUser(?MgUsers $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|MgUsers[]
     */
    public function getReviser(): Collection
    {
        return $this->reviser;
    }

    public function addReviser(MgUsers $reviser): self
    {
        if (!$this->reviser->contains($reviser)) {
            $this->reviser[] = $reviser;
        }

        return $this;
    }

    public function removeReviser(MgUsers $reviser): self
    {
        if ($this->reviser->contains($reviser)) {
            $this->reviser->removeElement($reviser);
        }

        return $this;
    }

    public function getSupplier(): ?MgSuppliers
    {
        return $this->supplier;
    }

    public function setSupplier(?MgSuppliers $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @return Collection|MgProductsLang[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(MgProductsLang $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setProduct($this);
        }

        return $this;
    }

    public function removeContent(MgProductsLang $content): self
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            // set the owning side to null (unless already changed)
            if ($content->getProduct() === $this) {
                $content->setProduct(null);
            }
        }

        return $this;
    }

    public function getGamme(): ?MgGammes
    {
        return $this->gamme;
    }

    public function setGamme(?MgGammes $gamme): self
    {
        $this->gamme = $gamme;

        return $this;
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
    public function getMgProducts(): Collection
    {
        return $this->mgProducts;
    }

    public function addMgProduct(self $mgProduct): self
    {
        if (!$this->mgProducts->contains($mgProduct)) {
            $this->mgProducts[] = $mgProduct;
            $mgProduct->setParent($this);
        }

        return $this;
    }

    public function removeMgProduct(self $mgProduct): self
    {
        if ($this->mgProducts->contains($mgProduct)) {
            $this->mgProducts->removeElement($mgProduct);
            // set the owning side to null (unless already changed)
            if ($mgProduct->getParent() === $this) {
                $mgProduct->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgCategories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(MgCategories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(MgCategories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|MgProductsImages[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(MgProductsImages $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(MgProductsImages $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }
}
