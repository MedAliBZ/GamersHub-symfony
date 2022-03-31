<?php

namespace App\Entity;
   
use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="carts")
     * @Groups("post:read")
     */
    private $product;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("post:read")
     */
    private $quantity;
 
    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="carts")
     * @Groups("post:read")
     */
    private $myOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?products
    {
        return $this->product;
    }

    public function setProduct(?products $product): self
    {
        $this->product = $product;

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

    public function getMyOrder(): ?order
    {
        return $this->myOrder;
    }

    public function setMyOrder(?order $myOrder): self
    {
        $this->myOrder = $myOrder;

        return $this;
    }
}
