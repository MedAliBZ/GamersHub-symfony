<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="this field is required")
     * @Assert\Length (min=5)
     * @Assert\Length (max=20)
     * @Assert\Regex(pattern="/[a-zA-Z]/" , message="the name cannot be a number")
     */
    private $nameProduct;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank (message="this field is required")
     * @Assert\PositiveOrZero
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class,inversedBy="products")
     * @Assert\NotBlank (message="this field is required")
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank (message="this field is required")
     * @Assert\PositiveOrZero
     */
    private $quantityStocked;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

      /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank (message="this field is required")
     * @Assert\Length (min=10)
     * @Assert\Length (max=300)
     */

    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $modificationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="product",cascade={"remove"})
     */
    private $carts;

    /**
     * @ORM\OneToMany(targetEntity=WishList::class, mappedBy="product")
     */
    private $wishList;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameProduct(): ?string
    {
        return $this->nameProduct;
    }

    public function setNameProduct(?string $nameProduct): self
    {
        $this->nameProduct = $nameProduct;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }
     
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getQuantityStocked(): ?int
    {
        return $this->quantityStocked;
    }

    public function setQuantityStocked(?int $quantityStocked): self
    {
        $this->quantityStocked = $quantityStocked;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?\DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setProduct($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getProduct() === $this) {
                $cart->setProduct(null);
            }
        }

        return $this;
    }

   

   
}
