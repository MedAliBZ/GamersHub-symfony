<?php

namespace App\Entity;

use App\Repository\MockCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MockCommandeRepository::class)
 */
class MockCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity=Livraison::class, mappedBy="commande", cascade={"persist", "remove"})
     */
    private $livraison;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        // unset the owning side of the relation if necessary
        if ($livraison === null && $this->livraison !== null) {
            $this->livraison->setCommande(null);
        }

        // set the owning side of the relation if necessary
        if ($livraison !== null && $livraison->getCommande() !== $this) {
            $livraison->setCommande($this);
        }

        $this->livraison = $livraison;

        return $this;
    }
}
