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

use App\Repository\MatchsRepository;



/**
 * @ORM\Entity(repositoryClass=MatchsRepository::class)
 */
class Matchs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */

    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("post:read")
     */
    private $match_date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="this field is required")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Your result must be at least {{ limit }} characters long",
     * )

     * @Groups("post:read")
     */
    private $result;




    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $MatchName;

    /**
     * @ORM\ManyToMany(targetEntity=Teams::class, inversedBy="matchs")
     */
    private $teams;





    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->teamb = new ArrayCollection();

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
    public function addTeam(Teams $team): self
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






   
}
