<?php

namespace App\Entity;

use App\Repository\RewardsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=RewardsRepository::class)
 */
class Rewards
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tournaments::class, inversedBy="rewards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $TournamentId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournamentId(): ?Tournaments
    {
        return $this->TournamentId;
    }

    public function setTournamentId(?Tournaments $TournamentId): self
    {
        $this->TournamentId = $TournamentId;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
