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
     * 
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Your result must be at least {{ limit }} characters long",
     * )
     
     */
    private $result;

    /**
     * @ORM\OneToMany(targetEntity=Teams::class, mappedBy="matchs", cascade={"remove"})
     */
    private $teams_id;

     /**
     * @ORM\ManyToOne(targetEntity=Teams::class, inversedBy="match_id")
     */
    private $teams;


    /**
     * @ORM\OneToMany(targetEntity=Teams::class, mappedBy="match1")
     */
    private $team1;

 
  

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

  
    public function getTeams(): ?Teams
    {
        return $this->teams;
    }

    public function setTeams(?Teams $team): self
    {
        $this->team = $team;

        return $this;
    }
  
    public function __toString(){
        // to show the name of the Category in the select
        return ("$this->id");
        
        // to show the id of the Category in the select
        // return $this->id;
    }


   
}
