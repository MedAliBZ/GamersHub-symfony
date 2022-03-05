<?php

namespace App\Entity;

use App\Repository\SessioncoachingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SessioncoachingRepository::class)
 */
class Sessioncoaching
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Coach::class, inversedBy="sessioncoachings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $coach;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sessioncoachings")
     * @ORM\JoinColumn(nullable=true)
     *
     */
    private $user;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThanOrEqual("today")
     * @Assert\NotBlank (message="this field is required")
     */
    private $date_debut;

    /**
     * @ORM\Column(type="date")
     * * @Assert\GreaterThanOrEqual("today")
     * @Assert\NotBlank (message="this field is required")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\GreaterThan(value=0,message="price should be greater than 0")
     * @Assert\NotBlank (message="this field is required")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $background_color;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $border_color;

    /**
     * @ORM\Column(type="string", length=1000)
     * @Assert\NotBlank (message="this field is required")
     * @Assert\Length (min=5)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $text_color;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(?Coach $coach): self
    {
        $this->coach = $coach;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut = null): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin = null): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->background_color;
    }

    public function setBackgroundColor(string $background_color): self
    {
        $this->background_color = $background_color;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->border_color;
    }

    public function setBorderColor(string $border_color): self
    {
        $this->border_color = $border_color;

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

    public function getTextColor(): ?string
    {
        return $this->text_color;
    }

    public function setTextColor(string $text_color): self
    {
        $this->text_color = $text_color;

        return $this;
    }
}
