<?php

namespace App\Entity;

use App\Repository\MatchsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MatchsRepository::class)
 */
class Matchs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $match_date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="this field is required")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Your result must be at least {{ limit }} characters long",
     * )
     
     */
    private $result;




    /**
     * @ORM\Column(type="string", length=255)
     */
    private $MatchName;

    /**
     * @ORM\ManyToMany(targetEntity=Teams::class, inversedBy="matchs")
     */
    private $teams;

    /**
     * @ORM\ManyToMany(targetEntity=Teams::class)
     */
    private $second;



    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->teamb = new ArrayCollection();
        $this->second = new ArrayCollection();
    }

 
  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchDate(): ?\DateTimeInterface
    {
        return $this->match_date;
    }

    public function setMatchDate(\DateTimeInterface $match_date): self
    {
        $this->match_date = $match_date;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }



    public function getMatchName(): ?string
    {
        return $this->MatchName;
    }

    public function setMatchName(string $MatchName): self
    {
        $this->MatchName = $MatchName;

        return $this;
    }
   


    /**
     * @return Collection|Teams[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }
    public function setTeams(Teams $team): self
    {
        if (!$this->teams->contains($team)) {
        $this->teams[] = $team;
    }

        return $this;
    }



    public function removeTeam(Teams $team): self
    {
        $this->teams->removeElement($team);

        return $this;
    }
    public function __toString(){
        // to show the name of the Category in the select
        return(string) $this->getMatchName();
        // to show the id of the Category in the select
        // return $this->id;
    }

    /**
     * @return Collection|Teams[]
     */
    public function getSecond(): Collection
    {
        return $this->second;
    }
    public function setSecond(Teams $second): self
    {
        if (!$this->teams->contains($second)) {
            $this->teams[] = $this->second;
        }

        return $this;
    }


    public function removeSecond(Teams $second): self
    {
        $this->second->removeElement($second);

        return $this;
    }




   
}
