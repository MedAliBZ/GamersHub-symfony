<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
 

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalprice;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCanceled;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="myOrder",cascade={"remove"})
     */
    private $carts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaid;
    




    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalprice(): ?float
    {
        return $this->totalprice;
    }

    public function setTotalprice(?float $totalprice): self
    {
        $this->totalprice = $totalprice;

        return $this;
    }

    public function getIsCanceled(): ?bool
    {
        return $this->isCanceled;
    }

    public function setIsCanceled(?bool $isCanceled): self
    {
        $this->isCanceled = $isCanceled;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

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
            $cart->setMyOrder($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getMyOrder() === $this) {
                $cart->setMyOrder(null);
            }
        }

        return $this;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }
}
