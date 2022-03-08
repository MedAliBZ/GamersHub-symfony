<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="This field cannot be blank.")
     * @Groups("post:read")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable= true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     * @Assert\NotBlank(message="This field cannot be blank.")
     * @Assert\Email(message="Wrong format.")
     * @Groups("post:read")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $secondName;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\LessThanOrEqual("-13 years", message="You should be at least 13 years old.")
     * @Groups("post:read")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $lastUpdated;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Coins cannot be negative.",
     * )
     */
    private $coins;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class, mappedBy="users")
     */
    private $games;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $oauth;

    /**
     * @ORM\OneToMany(targetEntity=MissionsDone::class, mappedBy="user")
     */
    private $missions;
    /**
     * @ORM\OneToOne(targetEntity=Coach::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $coach;

    /**
     * @ORM\OneToMany(targetEntity=Sessioncoaching::class, mappedBy="user", orphanRemoval=true)
     */
    private $sessioncoachings;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $blogs;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity=Player::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $player;

    /**
     * @ORM\OneToMany(targetEntity=Spam::class, mappedBy="user", cascade={"persist","remove"})
     */
    private $spam;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="user", cascade={"persist","remove"})
     */
    private $ratings;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->sessioncoachings = new ArrayCollection();
        $this->spam = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
        return (string)$this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    public function setSecondName(?string $secondName): self
    {
        $this->secondName = $secondName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getLastUpdated(): ?\DateTimeInterface
    {
        return $this->lastUpdated;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(?bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getCoins(): ?int
    {
        return $this->coins;
    }

    public function setCoins(?int $coins): self
    {
        $this->coins = $coins;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(?bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addUser($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            $game->removeUser($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->username;
    }

    public function getOauth(): ?bool
    {
        return $this->oauth;
    }

    public function setOauth(?bool $oauth): self
    {
        $this->oauth = $oauth;

        return $this;
    }



    /**
     * @return Collection|MissionsDone[]
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMissions(MissionsDone $missions): self
    {
        if (!$this->missions->contains($missions)) {
            $this->missions[] = $missions;
            $missions->setUser($this);
        }

        return $this;
    }

    public function removeMissions(MissionsDone $missions): self
    {
        if ($this->missions->removeElement($missions)) {
            // set the owning side to null (unless already changed)
            if ($missions->getUser() === $this) {
                $missions->setUser(null);
            }
        }

        return $this;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(Coach $coach): self
    {
        // set the owning side of the relation if necessary
        if ($coach->getUser() !== $this) {
            $coach->setUser($this);
        }

        $this->coach = $coach;

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
            $sessioncoaching->setUser($this);
        }

        return $this;
    }

    public function removeSessioncoaching(Sessioncoaching $sessioncoaching): self
    {
        if ($this->sessioncoachings->removeElement($sessioncoaching)) {
            // set the owning side to null (unless already changed)
            if ($sessioncoaching->getUser() === $this) {
                $sessioncoaching->setUser(null);
            }
        }

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        // set the owning side of the relation if necessary
        if ($player->getUser() !== $this) {
            $player->setUser($this);
        }

        $this->player = $player;

        return $this;
    }

    /**
     * @return Collection<int, Spam>
     */
    public function getSpam(): Collection
    {
        return $this->spam;
    }

    public function addSpam(Spam $spam): self
    {
        if (!$this->spam->contains($spam)) {
            $this->spam[] = $spam;
            $spam->setUser($this);
        }

        return $this;
    }

    public function removeSpam(Spam $spam): self
    {
        if ($this->spam->removeElement($spam)) {
            // set the owning side to null (unless already changed)
            if ($spam->getUser() === $this) {
                $spam->setUser(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getUser() === $this) {
                $rating->setUser(null);
            }
        }

        return $this;
    }
}
