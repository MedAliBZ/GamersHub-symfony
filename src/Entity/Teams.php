<?php

namespace App\Entity;

use App\Repository\TeamsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TeamsRepository::class)
 */
class Teams
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
     *      min = 2,
     *      max = 20,
     *      minMessage = "Your team name must be at least {{ limit }} characters long",
     *      maxMessage = "Your team name cannot be longer than {{ limit }} characters"
     * )
     */
     
    private $Team_name;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Assert\Length(
     *      min = 1,
     *      max = 1,
     *      minMessage = "Your gamers number must be at least {{ limit }} characters long",
     *      maxMessage = "Your gamers number cannot be longer than {{ limit }} characters"
     * )
     */
     
    private $gamersNb;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rank
    ;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verified;

    /**
     * @ORM\ManyToOne(targetEntity=Matchs::class, inversedBy="team_id")
     */
    private $matchs;
     /**
     * @ORM\OneToMany(targetEntity=Matchs::class, mappedBy="teams", cascade={"remove"})
     */
    private $match_id;


  
    public function getId(): ?int
    {
        return $this->id;
    }
    

    public function getTeamName(): ?string
    {
        return $this->Team_name;
    }

    public function setTeamName(string $Team_name): self
    {
        $this->Team_name = $Team_name;

        return $this;
    }

    public function getGamersNb(): ?int
    {
        return $this->gamersNb;
    }

    public function setGamersNb(int $gamersNb): self
    {
        $this->gamersNb = $gamersNb;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getMatchs(): ?Matchs
    {
        return $this->matchs;
    }

    public function setMatchs(?Matchs $matchs): self
    {
        $this->matchs = $matchs;

        return $this;
    }
    public function __toString(){
        // to show the name of the Category in the select
        return ("$this->id");
        // to show the id of the Category in the select
        // return $this->id;
    }



}
