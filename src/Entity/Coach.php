<?php

namespace App\Entity;

use App\Repository\CoachRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=CoachRepository::class)
 */
class Coach
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000)
     * @Assert\NotBlank (message="this field is required")
     * @Assert\Length (min=5)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="coach")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="coaches")
     * @ORM\JoinColumn(nullable=true, onDelete="set null")
     */
    private $game;

    /**
     * @ORM\OneToMany(targetEntity=Sessioncoaching::class, mappedBy="coach", orphanRemoval=true)
     */
    private $sessioncoachings;

    public function __construct()
    {
        $this->sessioncoachings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection|Sessioncoaching[]
     */
    public function getSessioncoachings(): Collection
    {
        return $this->sessioncoachings;
    }

    public function addSessioncoaching(Sessioncoaching $sessioncoaching): self
    {
        if (!$this->sessioncoachings->contains($sessioncoaching)) {
            $this->sessioncoachings[] = $sessioncoaching;
            $sessioncoaching->setCoach($this);
        }

        return $this;
    }

    public function removeSessioncoaching(Sessioncoaching $sessioncoaching): self
    {
        if ($this->sessioncoachings->removeElement($sessioncoaching)) {
            // set the owning side to null (unless already changed)
            if ($sessioncoaching->getCoach() === $this) {
                $sessioncoaching->setCoach(null);
            }
        }

        return $this;
    }
}
