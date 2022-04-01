<?php

namespace App\Entity;

use App\Repository\LivreurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LivreurRepository::class)
 */
class Livreur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(
     *      min = 3,
     *      max = 8,
     *      minMessage = "Your name must be at least {{ limit }} characters long",
     *      maxMessage = "Your name cannot be longer than {{ limit }} characters"
     * )
     *
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\Length(
     *      min = 3,
     *      max = 8,
     *      minMessage = "Your last name must be at least {{ limit }} characters long",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters"
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Your cin must be at least {{ limit }} characters long",
     *      maxMessage = "Your cin cannot be longer than {{ limit }} characters"
     * )
     * 
     */
    private $cin;

    /**
     * @ORM\Column(type="float")
     */
    private $salaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zone_geographique;

    /**
     * @ORM\OneToMany(targetEntity=Livraison::class, mappedBy="livreur", cascade={"remove"})
     */
    private $livraisons;

    public function __construct()
    {
        $this->livraisons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(float $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getZoneGeographique(): ?string
    {
        return $this->zone_geographique;
    }

    public function setZoneGeographique(string $zone_geographique): self
    {
        $this->zone_geographique = $zone_geographique;

        return $this;
    }

    /**
     * @return Collection|Livraison[]
     */
    public function getLivraisons(): Collection
    {
        return $this->livraisons;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons[] = $livraison;
            $livraison->setLivreur($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraisons->removeElement($livraison)) {
            // set the owning side to null (unless already changed)
            if ($livraison->getLivreur() === $this) {
                $livraison->setLivreur(null);
            }
        }

        return $this;
    }
}
