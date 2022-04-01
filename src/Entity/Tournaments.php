<?php

namespace App\Entity;

use App\Repository\TournamentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=TournamentsRepository::class)
 */
class Tournaments
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
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Oupsss too short!!{{ limit }} characters long",
     *      maxMessage = "Oupsss too long!! {{ limit }} characters"
     * )
     */

    private $name;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="this field is required")
     * @Assert\NotBlank(message = "Oupsss write something ")
     */
    private $decription;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="this field is required")
     * @Assert\Range(
     *      min= 6,
     *      notInRangeMessage ="Oupsss you must have at least {{ min }} teams in a tournament" ,
     * )
     */
    private $teamSize;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="this field is required")
     * )
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="this field is required")
     */
    private $finishDate;

    /**
     * @ORM\OneToMany(targetEntity=Rewards::class, mappedBy="TournamentId", cascade={"persist" , "remove"})
     */
    private $rewards;

    /**
     * @ORM\OneToMany(targetEntity=Subscribe::class, mappedBy="tournament", cascade={"persist", "remove"})
     */
    private $subscribes;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxT;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $images;

    public function __construct()
    {
        $this->rewards = new ArrayCollection();
        $this->subscribes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDecription(): ?string
    {
        return $this->decription;
    }

    public function setDecription(string $decription): self
    {
        $this->decription = $decription;

        return $this;
    }

    public function getTeamSize(): ?int
    {
        return $this->teamSize;
    }

    public function setTeamSize(int $teamSize): self
    {
        $this->teamSize = $teamSize;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFinishDate(): ?\DateTimeInterface
    {
        return $this->finishDate;
    }

    public function setFinishDate(\DateTimeInterface $finishDate): self
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    /**
     * @return Collection|Rewards[]
     */
    public function getRewards(): Collection
    {
        return $this->rewards;
    }

    public function addReward(Rewards $reward): self
    {
        if (!$this->rewards->contains($reward)) {
            $this->rewards[] = $reward;
            $reward->setTournamentId($this);
        }

        return $this;
    }

    public function removeReward(Rewards $reward): self
    {
        if ($this->rewards->removeElement($reward)) {
            // set the owning side to null (unless already changed)
            if ($reward->getTournamentId() === $this) {
                $reward->setTournamentId(null);
            }
        }

        return $this;
    }
    public function __toString(){
        // to show the name of the Category in the select
        return ("$this->id");
        // to show the id of the Category in the select
        // return $this->id;
    }

    /**
     * @return Collection<int, Subscribe>
     */
    public function getSubscribes(): Collection
    {
        return $this->subscribes;
    }

    public function addSubscribe(Subscribe $subscribe): self
    {
        if (!$this->subscribes->contains($subscribe)) {
            $this->subscribes[] = $subscribe;
            $subscribe->setTournament($this);
        }

        return $this;
    }

    public function removeSubscribe(Subscribe $subscribe): self
    {
        if ($this->subscribes->removeElement($subscribe)) {
            // set the owning side to null (unless already changed)
            if ($subscribe->getTournament() === $this) {
                $subscribe->setTournament(null);
            }
        }

        return $this;
    }

    public function getMaxT(): ?int
    {
        return $this->maxT;
    }

    public function setMaxT(int $maxT): self
    {
        $this->maxT = $maxT;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(string $images): self
    {
        $this->images = $images;

        return $this;
    }

}
